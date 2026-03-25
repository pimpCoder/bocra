<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * Admin: List all users with optional filters.
     */
    public function index(Request $request)
    {
        $query = User::orderBy('created_at', 'desc');

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->has('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'LIKE', "%{$term}%")
                  ->orWhere('email', 'LIKE', "%{$term}%");
            });
        }

        return response()->json(
            $query->paginate(20)->through(fn($u) => $this->userResource($u)),
            200
        );
    }

    /**
     * Admin: View a single user.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'user'  => $this->userResource($user),
            'stats' => [
                'complaints'   => $user->complaints()->count(),
                'licenses'     => $user->licenseApplications()->count(),
                'domains'      => $user->domainRegistrations()->count(),
                'notifications'=> $user->notifications()->count(),
            ],
        ], 200);
    }

    /**
     * Admin: Create a staff or admin user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:8',
            'role'         => 'required|in:citizen,business,staff,admin',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'password'     => Hash::make($validated['password']),
            'role'         => $validated['role'],
            'phone_number' => $validated['phone_number'] ?? null,
            'is_active'    => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => "User created with role: {$validated['role']}.",
            'user'    => $this->userResource($user),
        ], 201);
    }

    /**
     * Admin: Assign / change a user's role.
     */
    public function assignRole(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'role' => 'required|in:citizen,business,staff,admin',
        ]);

        // Prevent admin from demoting themselves
        if ($user->id === auth('sanctum')->id() && $validated['role'] !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'You cannot change your own admin role.',
            ], 422);
        }

        $user->update(['role' => $validated['role']]);

        return response()->json([
            'success' => true,
            'message' => "Role updated to '{$validated['role']}' for {$user->name}.",
            'user'    => $this->userResource($user->fresh()),
        ], 200);
    }

    /**
     * Admin: Activate a user account.
     */
    public function activate(string $id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => true]);

        return response()->json([
            'success' => true,
            'message' => "{$user->name}'s account has been activated.",
        ], 200);
    }

    /**
     * Admin: Deactivate (soft-ban) a user account.
     */
    public function deactivate(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth('sanctum')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot deactivate your own account.',
            ], 422);
        }

        $user->update(['is_active' => false]);

        // Revoke all tokens immediately
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => "{$user->name}'s account has been deactivated.",
        ], 200);
    }

    /**
     * Admin: Hard delete a user.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth('sanctum')->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account.',
            ], 422);
        }

        $user->tokens()->delete();
        $user->delete();

        return response()->json(null, 204);
    }

    /**
     * Admin: System-wide user stats.
     */
    public function stats()
    {
        return response()->json([
            'total'    => User::count(),
            'active'   => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'by_role'  => User::selectRaw('role, COUNT(*) as count')
                              ->groupBy('role')
                              ->pluck('count', 'role'),
        ], 200);
    }

    // -------------------------------------------------------------------------
    private function userResource(User $user): array
    {
        return [
            'id'                => $user->id,
            'name'              => $user->name,
            'email'             => $user->email,
            'role'              => $user->role,
            'phone_number'      => $user->phone_number,
            'national_id'       => $user->national_id,
            'organization_name' => $user->organization_name,
            'is_active'         => $user->is_active,
            'last_login_at'     => $user->last_login_at,
            'created_at'        => $user->created_at,
        ];
    }
}