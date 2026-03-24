<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ComplaintController;

/*
|--------------------------------------------------------------------------
| Authenticated user helper route
|--------------------------------------------------------------------------
*/
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Public Complaint Routes (no login required)
|--------------------------------------------------------------------------
*/
Route::get('/complaints',          [ComplaintController::class, 'index']);
Route::post('/complaints',         [ComplaintController::class, 'store']);
Route::get('/complaints/{id}',     [ComplaintController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Protected Complaint Routes (must be logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/complaints/{id}',    [ComplaintController::class, 'update']);
    Route::delete('/complaints/{id}', [ComplaintController::class, 'destroy']);

    // Admin-specific actions
    Route::put('/admin/complaints/{id}/status', [ComplaintController::class, 'updateStatus']);
    Route::put('/admin/complaints/{id}/assign',  [ComplaintController::class, 'assign']);
});

/*
|--------------------------------------------------------------------------
| Test Route
|--------------------------------------------------------------------------
*/
Route::post('/additions', function (Request $req) {
    return response()->json([
        'success' => true,
        'message' => 'Data received successfully',
        'data'    => $req->all()
    ], 201);
});