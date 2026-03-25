<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register a new user.
     * Role defaults to 'citizen' — admin/staff assigned separately.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:8|confirmed',
            'role'              => 'nullable|in:citizen,business',
            'phone_number'      => 'nullable|string|max:20',
            'national_id'       => 'nullable|string|max:50',
            'organization_name' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'role'              => $validated['role'] ?? 'citizen',
            'phone_number'      => $validated['phone_number'] ?? null,
            'national_id'       => $validated['national_id'] ?? null,
            'organization_name' => $validated['organization_name'] ?? null,
            'is_active'         => true,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful.',
            'user'    => $this->userResource($user),
            'token'   => $token,
        ], 201);
    }

    /**
     * Login and return a Sanctum token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been deactivated. Contact an administrator.',
            ], 403);
        }

        // Revoke old tokens to enforce single-session (optional — remove if multi-device needed)
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        // Track last login
        $user->update(['last_login_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'user'    => $this->userResource($user),
            'token'   => $token,
        ], 200);
    }

    /**
     * Get the authenticated user's profile.
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user'    => $this->userResource($request->user()),
        ], 200);
    }

    /**
     * Update own profile.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'              => 'sometimes|string|max:255',
            'phone_number'      => 'sometimes|string|max:20',
            'national_id'       => 'sometimes|string|max:50',
            'organization_name' => 'sometimes|string|max:255',
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'user'    => $this->userResource($user->fresh()),
        ], 200);
    }

    /**
     * Change own password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.',
            ], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Revoke all tokens — force re-login after password change
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully. Please log in again.',
        ], 200);
    }

    /**
     * Logout — revoke current token.
     */
    public function logout(Request $request): \Illuminate\Http\JsonResponse
{
    /** @var \Laravel\Sanctum\PersonalAccessToken|null $token */
    $token = $request->user()->currentAccessToken();
 
    // TransientToken (session-based) doesn't have delete()
    // PersonalAccessToken (API token) does — check before calling
    if ($token instanceof \Laravel\Sanctum\PersonalAccessToken) {
        $token->delete();
    }
 
    return response()->json([
        'success' => true,
        'message' => 'Logged out successfully.',
    ], 200);
}
 
/**
 * Logout from all devices — revoke all tokens.
 */
public function logoutAll(Request $request): \Illuminate\Http\JsonResponse
{
    $request->user()->tokens()->delete();
 
    return response()->json([
        'success' => true,
        'message' => 'Logged out from all devices.',
    ], 200);
}

    // -------------------------------------------------------------------------
    // Private helper — consistent user shape across all responses
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