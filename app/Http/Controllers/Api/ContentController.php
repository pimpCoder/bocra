<?php

namespace App\Http\Controllers\Api;

use App\Models\Content;
use App\Models\User;
use App\Services\NotificationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function __construct(protected NotificationService $notificationService) {}

    /**
     * Public: List all published content.
     * Admin: List all content including drafts and archived.
     */
    public function index(Request $request)
    {
        $isAdmin = auth('sanctum')->check() &&
                   auth('sanctum')->user()->role === 'admin';

        $query = Content::with('createdBy:id,name')
                        ->orderBy('published_at', 'desc');

        // Non-admins only see published content
        if (!$isAdmin) {
            $query->published();
        }

        // Filter by category
        if ($request->has('category')) {
            $query->ofCategory($request->category);
        }

        // Filter by status (admin only)
        if ($isAdmin && $request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search by keyword
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Filter by tag
        if ($request->has('tag')) {
            $query->where('tags', 'LIKE', '%' . $request->tag . '%');
        }

        $contents = $query->paginate(12);

        return response()->json($contents, 200);
    }

    /**
     * Public: View a single published content item.
     * Admin: Can view any content regardless of status.
     */
    public function show(string $id)
    {
        $isAdmin = auth('sanctum')->check() &&
                   auth('sanctum')->user()->role === 'admin';

        $query = Content::with('createdBy:id,name');

        if (!$isAdmin) {
            $query->published();
        }

        $content = $query->findOrFail($id);

        return response()->json($content, 200);
    }

    /**
     * Admin: Create new content (saved as draft by default).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'body'     => 'required|string',
            'category' => 'required|string|max:100',
            'tags'     => 'nullable|array',
            'tags.*'   => 'string|max:50',
            'status'   => 'nullable|in:draft,published,archived',
        ]);

        $status      = $validated['status'] ?? 'draft';
        $publishedAt = $status === 'published' ? now() : null;

        $content = Content::create([
            'title'        => $validated['title'],
            'body'         => $validated['body'],
            'category'     => $validated['category'],
            'tags'         => $validated['tags'] ?? [],
            'status'       => $status,
            'published_at' => $publishedAt,
            'created_by'   => auth('sanctum')->id(),
        ]);

        // Notify all users when something is published immediately
        if ($status === 'published') {
            $this->notifyAllUsers($content);
        }

        return response()->json([
            'success' => true,
            'message' => $status === 'published'
                            ? 'Content published successfully.'
                            : 'Content saved as draft.',
            'data'    => $content,
        ], 201);
    }

    /**
     * Admin: Update existing content.
     */
    public function update(Request $request, string $id)
    {
        $content = Content::findOrFail($id);

        $validated = $request->validate([
            'title'    => 'sometimes|string|max:255',
            'body'     => 'sometimes|string',
            'category' => 'sometimes|string|max:100',
            'tags'     => 'nullable|array',
            'tags.*'   => 'string|max:50',
            'status'   => 'sometimes|in:draft,published,archived',
        ]);

        // Set published_at when transitioning TO published
        if (
            isset($validated['status']) &&
            $validated['status'] === 'published' &&
            $content->status !== 'published'
        ) {
            $validated['published_at'] = now();

            // Notify users on publish
            $this->notifyAllUsers($content);
        }

        // Clear published_at if being archived or reverted to draft
        if (
            isset($validated['status']) &&
            in_array($validated['status'], ['draft', 'archived'])
        ) {
            $validated['published_at'] = null;
        }

        $content->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Content updated successfully.',
            'data'    => $content->fresh('createdBy:id,name'),
        ], 200);
    }

    /**
     * Admin: Publish a draft directly.
     */
    public function publish(string $id)
    {
        $content = Content::findOrFail($id);

        if ($content->status === 'published') {
            return response()->json([
                'success' => false,
                'message' => 'Content is already published.',
            ], 422);
        }

        $content->update([
            'status'       => 'published',
            'published_at' => now(),
        ]);

        $this->notifyAllUsers($content);

        return response()->json([
            'success' => true,
            'message' => 'Content published successfully.',
            'data'    => $content,
        ], 200);
    }

    /**
     * Admin: Unpublish — revert to draft.
     */
    public function unpublish(string $id)
    {
        $content = Content::findOrFail($id);

        $content->update([
            'status'       => 'draft',
            'published_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Content unpublished and reverted to draft.',
            'data'    => $content,
        ], 200);
    }

    /**
     * Admin: Archive content.
     */
    public function archive(string $id)
    {
        $content = Content::findOrFail($id);

        $content->update([
            'status'       => 'archived',
            'published_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Content archived.',
            'data'    => $content,
        ], 200);
    }

    /**
     * Public: Get all unique categories available.
     */
    public function categories()
    {
        $categories = Content::published()
                             ->distinct()
                             ->pluck('category');

        return response()->json($categories, 200);
    }

    /**
     * Admin: Content stats for dashboard.
     */
    public function stats()
    {
        return response()->json([
            'total'     => Content::count(),
            'published' => Content::where('status', 'published')->count(),
            'draft'     => Content::where('status', 'draft')->count(),
            'archived'  => Content::where('status', 'archived')->count(),
        ], 200);
    }

    /**
     * Admin: Delete content permanently.
     */
    public function destroy(string $id)
    {
        Content::findOrFail($id)->delete();
        return response()->json(null, 204);
    }

    // -------------------------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------------------------

    /**
     * Broadcast a notification to all users when content is published.
     */
    private function notifyAllUsers(Content $content): void
    {
        $userIds = User::pluck('id')->toArray();

        $this->notificationService->broadcastAlert(
            $userIds,
            "New {$content->category} published: {$content->title}"
        );
    }
}