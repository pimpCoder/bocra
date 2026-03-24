<?php

namespace App\Http\Controllers\Api;

use App\Models\LicenseApplication;
use App\Services\NotificationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function __construct(protected NotificationService $notificationService) {}

    // -------------------------------------------------------------------------
    // PUBLIC ROUTES
    // -------------------------------------------------------------------------

    /**
     * Public: Verify a license by business name or license type.
     * No authentication required — exposes only non-sensitive fields.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:2|max:255',
        ]);

        $results = LicenseApplication::approved()
            ->search($request->search)
            ->select([
                'id',
                'business_name',
                'license_type',
                'status',
                'validity_start',
                'validity_end',
                'submitted_at',
            ])
            ->orderBy('business_name')
            ->paginate(10);

        return response()->json($results, 200);
    }

    /**
     * Public: View a single approved license (for certificate display).
     */
    public function showPublic(string $id)
    {
        $license = LicenseApplication::approved()
            ->select([
                'id',
                'business_name',
                'license_type',
                'status',
                'validity_start',
                'validity_end',
                'submitted_at',
            ])
            ->findOrFail($id);

        return response()->json($license, 200);
    }

    // -------------------------------------------------------------------------
    // LICENSEE ROUTES (authenticated)
    // -------------------------------------------------------------------------

    /**
     * Licensee: Submit a new license application.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'license_type'  => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'documents'     => 'nullable|array',
            'documents.*'   => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // Handle multiple document uploads
        $uploadedDocs = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $uploadedDocs[] = $file->store('license_documents', 'public');
            }
        }

        $application = LicenseApplication::create([
            'user_id'       => auth('sanctum')->id(),
            'license_type'  => $validated['license_type'],
            'business_name' => $validated['business_name'],
            'documents'     => $uploadedDocs,
            'status'        => 'submitted',
            'submitted_at'  => now(),
        ]);

        // Notify applicant
        $this->notificationService->notify(
            auth('sanctum')->id(),
            "Your license application #{$application->id} for '{$application->business_name}' has been received.",
            'license'
        );

        return response()->json([
            'success'   => true,
            'message'   => 'License application submitted successfully.',
            'reference' => $application->id,
            'status'    => $application->status,
        ], 201);
    }

    /**
     * Licensee: View all their own applications.
     */
    public function myApplications(Request $request)
    {
        $query = LicenseApplication::where('user_id', auth('sanctum')->id())
                                   ->orderBy('submitted_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->paginate(10), 200);
    }

    /**
     * Licensee: Track a specific application.
     */
    public function track(string $id)
    {
        $application = LicenseApplication::where('id', $id)
            ->where('user_id', auth('sanctum')->id())
            ->select([
                'id',
                'business_name',
                'license_type',
                'status',
                'submitted_at',
                'reviewed_at',
                'rejection_reason',
                'validity_start',
                'validity_end',
            ])
            ->firstOrFail();

        return response()->json($application, 200);
    }

    // -------------------------------------------------------------------------
    // ADMIN ROUTES
    // -------------------------------------------------------------------------

    /**
     * Admin: List all applications with filters.
     */
    public function index(Request $request)
    {
        $query = LicenseApplication::with([
                     'user:id,name,email',
                     'reviewedBy:id,name'
                 ])
                 ->orderBy('submitted_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->search($request->search);
        }

        if ($request->has('license_type')) {
            $query->where('license_type', $request->license_type);
        }

        return response()->json($query->paginate(15), 200);
    }

    /**
     * Admin: View a single application in full detail.
     */
    public function show(string $id)
    {
        $application = LicenseApplication::with([
            'user:id,name,email,phone_number',
            'reviewedBy:id,name',
        ])->findOrFail($id);

        return response()->json($application, 200);
    }

    /**
     * Admin: Update application status (under_review / approved / rejected).
     */
    public function updateStatus(Request $request, string $id)
    {
        $application = LicenseApplication::findOrFail($id);

        $validated = $request->validate([
            'status'           => 'required|in:under_review,approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string',
            'validity_start'   => 'required_if:status,approved|nullable|date',
            'validity_end'     => 'required_if:status,approved|nullable|date|after:validity_start',
        ]);

        $updateData = [
            'status'      => $validated['status'],
            'reviewed_at' => now(),
            'reviewed_by' => auth('sanctum')->id(),
        ];

        if ($validated['status'] === 'rejected') {
            $updateData['rejection_reason'] = $validated['rejection_reason'];
        }

        if ($validated['status'] === 'approved') {
            $updateData['validity_start'] = $validated['validity_start'];
            $updateData['validity_end']   = $validated['validity_end'];
        }

        $application->update($updateData);

        // Notify the applicant
        if ($application->user_id) {
            $this->notificationService->licenseStatusUpdated(
                $application->user_id,
                $application->id,
                $validated['status']
            );
        }

        return response()->json([
            'success' => true,
            'message' => "Application {$validated['status']} successfully.",
            'data'    => $application->fresh(['user:id,name,email', 'reviewedBy:id,name']),
        ], 200);
    }

    /**
     * Admin: Dashboard stats.
     */
    public function stats()
    {
        return response()->json([
            'total'        => LicenseApplication::count(),
            'submitted'    => LicenseApplication::where('status', 'submitted')->count(),
            'under_review' => LicenseApplication::where('status', 'under_review')->count(),
            'approved'     => LicenseApplication::where('status', 'approved')->count(),
            'rejected'     => LicenseApplication::where('status', 'rejected')->count(),
        ], 200);
    }

    /**
     * Admin: Delete an application.
     */
    public function destroy(string $id)
    {
        LicenseApplication::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}