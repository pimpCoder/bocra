<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        // Must be authenticated first
        if (!auth('sanctum')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $user = auth('sanctum')->user();

        // Check if account is active
        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been deactivated. Contact an administrator.',
            ], 403);
        }

        // Check role
        if (!empty($roles) && !$user->hasRole($roles)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to perform this action.',
                'required_roles' => $roles,
                'your_role'      => $user->role,
            ], 403);
        }

        return $next($request);
    }
}