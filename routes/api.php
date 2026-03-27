<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminUserController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\LicenseController;
use App\Http\Controllers\Api\DomainController;

/*
|--------------------------------------------------------------------------
| AUTH — public routes (no token needed)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| AUTH — protected routes (token required)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
    Route::get('/me',              [AuthController::class, 'me']);
    Route::put('/profile',         [AuthController::class, 'updateProfile']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout',         [AuthController::class, 'logout']);
    Route::post('/logout-all',     [AuthController::class, 'logoutAll']);
});

/*
|--------------------------------------------------------------------------
| ADMIN — user management (admin only)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/users',                    [AdminUserController::class, 'index']);
    Route::post('/users',                   [AdminUserController::class, 'store']);
    Route::get('/users/stats',              [AdminUserController::class, 'stats']);
    Route::get('/users/{id}',               [AdminUserController::class, 'show']);
    Route::put('/users/{id}/role',          [AdminUserController::class, 'assignRole']);
    Route::put('/users/{id}/activate',      [AdminUserController::class, 'activate']);
    Route::put('/users/{id}/deactivate',    [AdminUserController::class, 'deactivate']);
    Route::delete('/users/{id}',            [AdminUserController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| COMPLAINTS
|--------------------------------------------------------------------------
*/
// Public
Route::post('/complaints',           [ComplaintController::class, 'store']);
Route::get('/complaints/track/{id}', [ComplaintController::class, 'track']);
Route::get('/complaints/{id}',       [ComplaintController::class, 'show']);

// Authenticated users
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/complaints',                   [ComplaintController::class, 'index']);
    Route::delete('/complaints/{id}',           [ComplaintController::class, 'destroy']);
});

// Staff + Admin only
Route::middleware(['auth:sanctum', 'role:staff,admin'])->group(function () {
    Route::get('/complaints/stats/summary',          [ComplaintController::class, 'stats']);
    Route::put('/complaints/{id}/status',            [ComplaintController::class, 'updateStatus']);
    Route::put('/complaints/{id}/priority',          [ComplaintController::class, 'updatePriority']);
    Route::put('/complaints/{id}/assign',            [ComplaintController::class, 'assign']);
});

/*
|--------------------------------------------------------------------------
| NOTIFICATIONS
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications',                [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count',   [NotificationController::class, 'unreadCount']);
    Route::put('/notifications/{id}/read',      [NotificationController::class, 'markAsRead']);
    Route::put('/notifications/read-all',       [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}',        [NotificationController::class, 'destroy']);
});

// Admin broadcast
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/notifications/broadcast', [NotificationController::class, 'broadcast']);
});

/*
|--------------------------------------------------------------------------
| CMS — CONTENT
|--------------------------------------------------------------------------
*/
// Public
Route::get('/contents',            [ContentController::class, 'index']);
Route::get('/contents/categories', [ContentController::class, 'categories']);
Route::get('/contents/{id}',       [ContentController::class, 'show']);

// Staff + Admin
Route::middleware(['auth:sanctum', 'role:staff,admin'])->group(function () {
    Route::post('/contents',                   [ContentController::class, 'store']);
    Route::put('/contents/{id}',               [ContentController::class, 'update']);
    Route::put('/contents/{id}/publish',       [ContentController::class, 'publish']);
    Route::put('/contents/{id}/unpublish',     [ContentController::class, 'unpublish']);
    Route::put('/contents/{id}/archive',       [ContentController::class, 'archive']);
    Route::get('/contents/stats/summary',      [ContentController::class, 'stats']);
});

// Admin only
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::delete('/contents/{id}',            [ContentController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| LICENSING
|--------------------------------------------------------------------------
*/
// Public
Route::get('/licenses/verify',      [LicenseController::class, 'verify']);
Route::get('/licenses/public/{id}', [LicenseController::class, 'showPublic']);

// Business + Admin (apply and track)
Route::middleware(['auth:sanctum', 'role:business,admin'])->group(function () {
    Route::post('/licenses',                [LicenseController::class, 'store']);
    Route::get('/licenses/my-applications', [LicenseController::class, 'myApplications']);
    Route::get('/licenses/track/{id}',      [LicenseController::class, 'track']);
});

// Staff + Admin (review and manage)
Route::middleware(['auth:sanctum', 'role:staff,admin'])->group(function () {
    Route::get('/licenses',                  [LicenseController::class, 'index']);
    Route::get('/licenses/{id}',             [LicenseController::class, 'show']);
    Route::put('/licenses/{id}/status',      [LicenseController::class, 'updateStatus']);
    Route::get('/licenses/stats/summary',    [LicenseController::class, 'stats']);
});

// Admin only
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::delete('/licenses/{id}',          [LicenseController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| DOMAIN REGISTRATION
|--------------------------------------------------------------------------
*/
// Public
Route::get('/domains/check',  [DomainController::class, 'checkAvailability']);
Route::get('/domains/lookup', [DomainController::class, 'lookup']);

// Authenticated users (citizen, business, admin)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/domains',           [DomainController::class, 'store']);
    Route::get('/domains/my-domains', [DomainController::class, 'myDomains']);
    Route::get('/domains/track/{id}', [DomainController::class, 'track']);
});

// Staff + Admin
Route::middleware(['auth:sanctum', 'role:staff,admin'])->group(function () {
    Route::get('/domains',                 [DomainController::class, 'index']);
    Route::get('/domains/{id}',            [DomainController::class, 'show']);
    Route::put('/domains/{id}/status',     [DomainController::class, 'updateStatus']);
    Route::get('/domains/stats/summary',   [DomainController::class, 'stats']);
});

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!'], 200);
});
// Admin only
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::delete('/domains/{id}',         [DomainController::class, 'destroy']);
});