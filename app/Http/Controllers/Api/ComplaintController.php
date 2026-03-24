<?php

namespace App\Http\Controllers\Api;

use App\Models\Complaint;
use App\Models\ComplaintStatusHistory;
use App\Models\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function __construct(protected \App\Services\NotificationService $notificationService) {}
    /**
     * List all complaints (admin) or user's own complaints (citizen).
     */
    public function index(Request $request)
    {
        $user = auth('sanctum')->check() ? auth('sanctum')->user() : null;

        $query = Complaint::with(['statusHistories', 'assignedTo:id,name']);

        // Citizens only see their own complaints
        if ($user && $user->role === 'citizen') {
            $query->where('user_id', $user->id);
        }

        // Filters (admin use)
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $complaints = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($complaints, 200);
    }

    /**
     * Submit a complaint — works for guests AND authenticated users.
     */
    public function store(Request $request)
    {
        $isAuthenticated = auth('sanctum')->check();
        $userId          = $isAuthenticated ? auth('sanctum')->id() : null;

        $rules = [
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'category'     => 'required|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'priority'     => 'nullable|in:low,medium,high',
            'evidence_file'=> 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'name'         => $isAuthenticated ? 'nullable|string|max:255' : 'required|string|max:255',
            'email'        => $isAuthenticated ? 'nullable|email|max:255' : 'required|email|max:255',
        ];

        $validated = $request->validate($rules);

        // Handle evidence file upload
        $evidencePath = null;
        if ($request->hasFile('evidence_file')) {
            $evidencePath = $request->file('evidence_file')
                                    ->store('evidence', 'public');
        }

        // Fall back to authenticated user's profile data if not provided
        if ($isAuthenticated) {
            $user             = auth('sanctum')->user();
            $validated['name']  = $validated['name']  ?? $user->name;
            $validated['email'] = $validated['email'] ?? $user->email;
        }

        $complaint = Complaint::create([
            'user_id'       => $userId,
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'phone_number'  => $validated['phone_number'] ?? null,
            'title'         => $validated['title'],
            'description'   => $validated['description'],
            'category'      => $validated['category'],
            'priority'      => $validated['priority'] ?? 'medium',
            'evidence_file' => $evidencePath,
            'status'        => 'pending',
        ]);

        // Log the initial status in history
        ComplaintStatusHistory::create([
            'complaint_id' => $complaint->id,
            'status'       => 'pending',
            'updated_by'   => $userId,
            'comments'     => 'Complaint submitted.',
        ]);

        
        if ($userId) {
            $this->notificationService->complaintSubmitted($userId, $complaint->id);
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Complaint submitted successfully.',
            'reference' => $complaint->id,
            'status'    => $complaint->status,
        ], 201);
    }

    /**
     * View a single complaint with its full status history.
     */
    public function show(string $id)
    {
        $complaint = Complaint::with([
            'statusHistories.updatedBy:id,name',
            'assignedTo:id,name'
        ])->findOrFail($id);

        return response()->json($complaint, 200);
    }

    /**
     * Track a complaint by reference ID — public, no auth needed.
     */
    public function track(string $id)
    {
        $complaint = Complaint::with('statusHistories:id,complaint_id,status,comments,created_at')
                              ->findOrFail($id);

        return response()->json([
            'reference'   => $complaint->id,
            'title'       => $complaint->title,
            'category'    => $complaint->category,
            'status'      => $complaint->status,
            'priority'    => $complaint->priority,
            'submitted_at'=> $complaint->created_at,
            'timeline'    => $complaint->statusHistories,
        ], 200);
    }

    /**
     * Admin: Update complaint status and log the change.
     */
    public function updateStatus(Request $request, string $id)
    {
        $complaint = Complaint::findOrFail($id);

        $validated = $request->validate([
            'status'   => 'required|in:pending,under_review,resolved,rejected',
            'comments' => 'nullable|string',
        ]);

        $complaint->update(['status' => $validated['status']]);

        // Log to status history
        ComplaintStatusHistory::create([
            'complaint_id' => $complaint->id,
            'status'       => $validated['status'],
            'updated_by'   => auth('sanctum')->id(),
            'comments'     => $validated['comments'] ?? null,
        ]);

        // Notify the complaint owner if they have an account
        if ($complaint->user_id) {
           $this->notificationService->complaintStatusUpdated(
            $complaint->user_id,
            $complaint->id,
            $validated['status']
        );
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'data'    => $complaint->fresh('statusHistories'),
        ], 200);
    }

    /**
     * Admin: Assign complaint to a staff member.
     */
    public function assign(Request $request, string $id)
    {
        $complaint = Complaint::findOrFail($id);

        $validated = $request->validate([
            'assigned_to' => 'required|integer|exists:users,id',
        ]);

        $complaint->update(['assigned_to' => $validated['assigned_to']]);

        ComplaintStatusHistory::create([
            'complaint_id' => $complaint->id,
            'status'       => $complaint->status,
            'updated_by'   => auth('sanctum')->id(),
            'comments'     => "Complaint assigned to staff ID {$validated['assigned_to']}.",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Complaint assigned successfully.',
            'data'    => $complaint->fresh('assignedTo:id,name'),
        ], 200);
    }

    /**
     * Admin: Update priority.
     */
    public function updatePriority(Request $request, string $id)
    {
        $complaint = Complaint::findOrFail($id);

        $validated = $request->validate([
            'priority' => 'required|in:low,medium,high',
        ]);

        $complaint->update(['priority' => $validated['priority']]);

        return response()->json([
            'success' => true,
            'message' => 'Priority updated.',
            'data'    => $complaint,
        ], 200);
    }

    /**
     * Admin: Dashboard stats.
     */
    public function stats()
    {
        return response()->json([
            'total'        => Complaint::count(),
            'pending'      => Complaint::where('status', 'pending')->count(),
            'under_review' => Complaint::where('status', 'under_review')->count(),
            'resolved'     => Complaint::where('status', 'resolved')->count(),
            'rejected'     => Complaint::where('status', 'rejected')->count(),
            'high_priority'=> Complaint::where('priority', 'high')->count(),
        ], 200);
    }

    /**
     * Delete a complaint (admin only).
     */
    public function destroy(string $id)
    {
        Complaint::destroy($id);
        return response()->json(null, 204);
    }
    
}