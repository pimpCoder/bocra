<?php

namespace App\Http\Controllers\Api;

use App\Models\DomainRegistration;
use App\Services\NotificationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function __construct(protected NotificationService $notificationService) {}

    // -------------------------------------------------------------------------
    // PUBLIC ROUTES
    // -------------------------------------------------------------------------

    /**
     * Public: Check if a domain name is available.
     * No authentication required.
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'domain_name' => 'required|string|max:255',
            'domain_type' => 'required|in:' . implode(',', DomainRegistration::ALLOWED_TYPES),
        ]);

        $fullDomain  = strtolower(trim($request->domain_name));
        $domainType  = $request->domain_type;

        // Validate domain name format: only alphanumeric and hyphens, no leading/trailing hyphens
        if (!preg_match('/^[a-z0-9]([a-z0-9\-]{0,61}[a-z0-9])?$/', $fullDomain)) {
            return response()->json([
                'available' => false,
                'message'   => 'Invalid domain name format. Only letters, numbers, and hyphens are allowed.',
            ], 422);
        }

        $exists = DomainRegistration::where('domain_name', $fullDomain)
                                    ->where('domain_type', $domainType)
                                    ->whereIn('status', ['pending', 'active'])
                                    ->exists();

        return response()->json([
            'domain'    => $fullDomain . $domainType,
            'available' => !$exists,
            'message'   => $exists
                              ? 'This domain is already registered or pending approval.'
                              : 'This domain is available for registration.',
        ], 200);
    }

    /**
     * Public: Look up a registered domain — shows non-sensitive info only.
     */
    public function lookup(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|max:255',
        ]);

        $search = strtolower(trim($request->domain));

        $domain = DomainRegistration::active()
            ->where(function ($q) use ($search) {
                // Support searching with or without extension
                $q->where('domain_name', $search)
                  ->orWhereRaw("CONCAT(domain_name, domain_type) = ?", [$search]);
            })
            ->select([
                'id',
                'domain_name',
                'domain_type',
                'status',
                'registration_date',
                'expiry_date',
            ])
            ->first();

        if (!$domain) {
            return response()->json([
                'found'   => false,
                'message' => 'No active domain registration found for this name.',
            ], 404);
        }

        return response()->json([
            'found'  => true,
            'domain' => [
                'full_domain'       => $domain->full_domain,
                'status'            => $domain->status,
                'registration_date' => $domain->registration_date,
                'expiry_date'       => $domain->expiry_date,
                'is_expired'        => $domain->is_expired,
            ],
        ], 200);
    }

    // -------------------------------------------------------------------------
    // AUTHENTICATED USER ROUTES
    // -------------------------------------------------------------------------

    /**
     * User: Submit a domain registration request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'domain_name' => 'required|string|max:255',
            'domain_type' => 'required|in:' . implode(',', DomainRegistration::ALLOWED_TYPES),
        ]);

        $domainName = strtolower(trim($validated['domain_name']));
        $domainType = $validated['domain_type'];

        // Validate domain format
        if (!preg_match('/^[a-z0-9]([a-z0-9\-]{0,61}[a-z0-9])?$/', $domainName)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid domain name format.',
            ], 422);
        }

        // Check availability before registering
        $taken = DomainRegistration::where('domain_name', $domainName)
                                   ->where('domain_type', $domainType)
                                   ->whereIn('status', ['pending', 'active'])
                                   ->exists();

        if ($taken) {
            return response()->json([
                'success' => false,
                'message' => 'This domain is already taken or pending approval.',
            ], 409);
        }

        $registration = DomainRegistration::create([
            'user_id'     => auth('sanctum')->id(),
            'domain_name' => $domainName,
            'domain_type' => $domainType,
            'status'      => 'pending',
        ]);

        // Notify applicant
        $this->notificationService->notify(
            auth('sanctum')->id(),
            "Your domain registration request for '{$registration->full_domain}' has been received and is pending review.",
            'domain'
        );

        return response()->json([
            'success'   => true,
            'message'   => 'Domain registration request submitted successfully.',
            'reference' => $registration->id,
            'domain'    => $registration->full_domain,
            'status'    => $registration->status,
        ], 201);
    }

    /**
     * User: View all their own domain registrations.
     */
    public function myDomains(Request $request)
    {
        $query = DomainRegistration::where('user_id', auth('sanctum')->id())
                                   ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $domains = $query->paginate(10);

        // Append computed attributes
        $domains->getCollection()->transform(function ($domain) {
            $domain->full_domain = $domain->full_domain;
            $domain->is_expired  = $domain->is_expired;
            return $domain;
        });

        return response()->json($domains, 200);
    }

    /**
     * User: Track a specific domain registration.
     */
    public function track(string $id)
    {
        $domain = DomainRegistration::where('id', $id)
            ->where('user_id', auth('sanctum')->id())
            ->select([
                'id',
                'domain_name',
                'domain_type',
                'status',
                'registration_date',
                'expiry_date',
                'rejection_reason',
                'reviewed_at',
            ])
            ->firstOrFail();

        return response()->json([
            'data'        => $domain,
            'full_domain' => $domain->full_domain,
            'is_expired'  => $domain->is_expired,
        ], 200);
    }

    // -------------------------------------------------------------------------
    // ADMIN ROUTES
    // -------------------------------------------------------------------------

    /**
     * Admin: List all domain registrations with filters.
     */
    public function index(Request $request)
    {
        $query = DomainRegistration::with([
                     'user:id,name,email',
                     'reviewedBy:id,name',
                 ])
                 ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->search($request->search);
        }

        if ($request->has('domain_type')) {
            $query->where('domain_type', $request->domain_type);
        }

        return response()->json($query->paginate(15), 200);
    }

    /**
     * Admin: View a single domain registration in full detail.
     */
    public function show(string $id)
    {
        $domain = DomainRegistration::with([
            'user:id,name,email,phone_number',
            'reviewedBy:id,name',
        ])->findOrFail($id);

        return response()->json($domain, 200);
    }

    /**
     * Admin: Approve or reject a domain registration.
     */
    public function updateStatus(Request $request, string $id)
    {
        $domain = DomainRegistration::findOrFail($id);

        $validated = $request->validate([
            'status'           => 'required|in:active,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string',
            'expiry_date'      => 'required_if:status,active|nullable|date|after:today',
        ]);

        $updateData = [
            'status'      => $validated['status'],
            'reviewed_by' => auth('sanctum')->id(),
            'reviewed_at' => now(),
        ];

        if ($validated['status'] === 'active') {
            $updateData['registration_date'] = now()->toDateString();
            $updateData['expiry_date']        = $validated['expiry_date'];
        }

        if ($validated['status'] === 'rejected') {
            $updateData['rejection_reason'] = $validated['rejection_reason'];
        }

        $domain->update($updateData);

        // Notify the applicant
        if ($domain->user_id) {
            $statusMessage = $validated['status'] === 'active'
                ? "Your domain '{$domain->full_domain}' has been approved and is now active."
                : "Your domain registration for '{$domain->full_domain}' has been rejected. Reason: {$validated['rejection_reason']}";

            $this->notificationService->notify(
                $domain->user_id,
                $statusMessage,
                'domain'
            );
        }

        return response()->json([
            'success' => true,
            'message' => "Domain registration {$validated['status']} successfully.",
            'data'    => $domain->fresh(['user:id,name,email', 'reviewedBy:id,name']),
        ], 200);
    }

    /**
     * Admin: Dashboard stats.
     */
    public function stats()
    {
        return response()->json([
            'total'    => DomainRegistration::count(),
            'pending'  => DomainRegistration::where('status', 'pending')->count(),
            'active'   => DomainRegistration::where('status', 'active')->count(),
            'rejected' => DomainRegistration::where('status', 'rejected')->count(),
            'expired'  => DomainRegistration::expired()->count(),
            'by_type'  => DomainRegistration::active()
                            ->selectRaw('domain_type, COUNT(*) as count')
                            ->groupBy('domain_type')
                            ->pluck('count', 'domain_type'),
        ], 200);
    }

    /**
     * Admin: Delete a domain registration.
     */
    public function destroy(string $id)
    {
        DomainRegistration::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}