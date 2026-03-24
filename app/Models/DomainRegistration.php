<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainRegistration extends Model
{
    protected $fillable = [
        'user_id',
        'domain_name',
        'domain_type',
        'status',
        'registration_date',
        'expiry_date',
        'reviewed_by',
        'rejection_reason',
        'reviewed_at',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'expiry_date'       => 'date',
        'reviewed_at'       => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    // Allowed domain extensions
    const ALLOWED_TYPES = ['.bw', '.co.bw', '.org.bw', '.net.bw', '.gov.bw', '.ac.bw'];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now())
                     ->where('status', 'active');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where('domain_name', 'LIKE', "%{$term}%");
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

    // Helper: full domain e.g. "mybusiness.co.bw"
    public function getFullDomainAttribute(): string
    {
        return $this->domain_name . $this->domain_type;
    }

    // Helper: check if domain is expired
    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}