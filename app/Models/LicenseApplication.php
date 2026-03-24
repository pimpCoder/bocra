<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseApplication extends Model
{
    protected $fillable = [
        'user_id',
        'license_type',
        'business_name',
        'documents',
        'status',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
        'rejection_reason',
        'validity_start',
        'validity_end',
    ];

    protected $casts = [
        'documents'    => 'array',
        'submitted_at' => 'datetime',
        'reviewed_at'  => 'datetime',
        'validity_start' => 'date',
        'validity_end'   => 'date',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('business_name', 'LIKE', "%{$term}%")
              ->orWhere('license_type', 'LIKE', "%{$term}%");
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}