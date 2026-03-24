<?php

namespace App\Http\Controllers\Api;

use App\Models\Complaint;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * Display a listing of complaints.
     */
    public function index()
    {
        return response()->json(Complaint::all(), 200);
    }

    /**
     * Store a newly created complaint.
     * Works for both guests and authenticated users.
     */
    public function store(Request $request)
    {
        // Determine if a user is logged in via the api guard
        $isAuthenticated = auth('sanctum')->check();
        $userId = $isAuthenticated ? auth('sanctum')->id() : null;

        // If guest, name and email become required. If authenticated, they're optional
        // since we can pull info from the user's account.
        $rules = [
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'category'     => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'name'         => $isAuthenticated ? 'nullable|string|max:255' : 'required|string|max:255',
            'email'        => $isAuthenticated ? 'nullable|email|max:255' : 'required|email|max:255',
        ];

        $validated = $request->validate($rules);

        // If authenticated and name/email not provided, fall back to the user's account details
        if ($isAuthenticated) {
            $user = auth('api')->user();
            $validated['name']  = $validated['name']  ?? $user->name;
            $validated['email'] = $validated['email'] ?? $user->email;
        }

        $complaint = Complaint::create([
            'user_id'      => $userId,
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? null,
            'title'        => $validated['title'],
            'description'  => $validated['description'],
            'category'     => $validated['category'] ?? null,
            'status'       => 'pending',
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Complaint submitted successfully',
            'reference' => $complaint->id,
        ], 201);
    }

    /**
     * Display a specific complaint.
     */
    public function show(string $id)
    {
        return response()->json(Complaint::findOrFail($id), 200);
    }

    /**
     * Update a complaint.
     */
    public function update(Request $request, string $id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->update($request->all());
        return response()->json($complaint, 200);
    }

    /**
     * Delete a complaint.
     */
    public function destroy(string $id)
    {
        Complaint::destroy($id);
        return response()->json(null, 204);
    }

    /**
 * Update the status of a complaint (admin only).
 */
public function updateStatus(Request $request, string $id)
{
    $complaint = Complaint::findOrFail($id);

    $validated = $request->validate([
        'status' => 'required|in:pending,in_progress,resolved,rejected'
    ]);

    $complaint->update(['status' => $validated['status']]);

    return response()->json([
        'success' => true,
        'message' => 'Status updated',
        'data'    => $complaint
    ], 200);
}

/**
 * Assign a complaint to a staff member (admin only).
 */
public function assign(Request $request, string $id)
{
    $complaint = Complaint::findOrFail($id);

    $validated = $request->validate([
        'assigned_to' => 'required|integer|exists:users,id'
    ]);

    $complaint->update(['assigned_to' => $validated['assigned_to']]);

    return response()->json([
        'success' => true,
        'message' => 'Complaint assigned successfully',
        'data'    => $complaint
    ], 200);
}
}