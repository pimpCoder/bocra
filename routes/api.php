<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\LicenseController;
use App\Http\Controllers\Api\DomainController;

// Authenticated user helper
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Public Complaint Routes
|--------------------------------------------------------------------------
*/
Route::post('/complaints',              [ComplaintController::class, 'store']);
Route::get('/complaints/track/{id}',    [ComplaintController::class, 'track']);  // must be before /{id}
Route::get('/complaints/{id}',          [ComplaintController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/complaints',               [ComplaintController::class, 'index']);
    Route::delete('/complaints/{id}',       [ComplaintController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Admin Only Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/complaints/stats',                      [ComplaintController::class, 'stats']);
    Route::put('/complaints/{id}/status',                [ComplaintController::class, 'updateStatus']);
    Route::put('/complaints/{id}/priority',              [ComplaintController::class, 'updatePriority']);
    Route::put('/complaints/{id}/assign',                [ComplaintController::class, 'assign']);
});


/*
|--------------------------------------------------------------------------
| Notification Routes — all require authentication
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications',                [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count',   [NotificationController::class, 'unreadCount']);
    Route::put('/notifications/{id}/read',      [NotificationController::class, 'markAsRead']);
    Route::put('/notifications/read-all',       [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}',        [NotificationController::class, 'destroy']);

    // Admin only
    Route::post('/notifications/broadcast',     [NotificationController::class, 'broadcast']);
});

/*
|--------------------------------------------------------------------------
| CMS Routes
|--------------------------------------------------------------------------
*/

// Public — no auth required
Route::get('/contents',              [ContentController::class, 'index']);
Route::get('/contents/categories',   [ContentController::class, 'categories']);
Route::get('/contents/{id}',         [ContentController::class, 'show']);

// Admin only
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/contents',                    [ContentController::class, 'store']);
    Route::put('/contents/{id}',                [ContentController::class, 'update']);
    Route::delete('/contents/{id}',             [ContentController::class, 'destroy']);
    Route::put('/contents/{id}/publish',        [ContentController::class, 'publish']);
    Route::put('/contents/{id}/unpublish',      [ContentController::class, 'unpublish']);
    Route::put('/contents/{id}/archive',        [ContentController::class, 'archive']);
    Route::get('/contents/stats/summary',       [ContentController::class, 'stats']);
});

/*
|--------------------------------------------------------------------------
| Licensing Routes
|--------------------------------------------------------------------------
*/

// Public — no auth required
Route::get('/licenses/verify',       [LicenseController::class, 'verify']);
Route::get('/licenses/public/{id}',  [LicenseController::class, 'showPublic']);

// Licensee — must be logged in
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/licenses',                [LicenseController::class, 'store']);
    Route::get('/licenses/my-applications', [LicenseController::class, 'myApplications']);
    Route::get('/licenses/track/{id}',      [LicenseController::class, 'track']);
});

// Admin only
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/licenses',                         [LicenseController::class, 'index']);
    Route::get('/licenses/{id}',                    [LicenseController::class, 'show']);
    Route::put('/licenses/{id}/status',             [LicenseController::class, 'updateStatus']);
    Route::get('/licenses/stats/summary',           [LicenseController::class, 'stats']);
    Route::delete('/licenses/{id}',                 [LicenseController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Domain Registration Routes
|--------------------------------------------------------------------------
*/

// Public — no auth required
Route::get('/domains/check',   [DomainController::class, 'checkAvailability']);
Route::get('/domains/lookup',  [DomainController::class, 'lookup']);

// Authenticated users
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/domains',              [DomainController::class, 'store']);
    Route::get('/domains/my-domains',    [DomainController::class, 'myDomains']);
    Route::get('/domains/track/{id}',    [DomainController::class, 'track']);
});

// Admin only
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/domains',                    [DomainController::class, 'index']);
    Route::get('/domains/{id}',               [DomainController::class, 'show']);
    Route::put('/domains/{id}/status',        [DomainController::class, 'updateStatus']);
    Route::get('/domains/stats/summary',      [DomainController::class, 'stats']);
    Route::delete('/domains/{id}',            [DomainController::class, 'destroy']);
});

// Test route
Route::post('/additions', function (Request $req) {
    return response()->json(['success' => true, 'data' => $req->all()], 201);
});