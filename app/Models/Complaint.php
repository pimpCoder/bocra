<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
     protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone_number',
        'title',
        'description',
        'category',
        'status',
        'priority',
        'evidence_file',
        'assigned_to'
    ];
    // Cast certain attributes to native types
    protected $casts = [
        'user_id' => 'integer',
        'assigned_to' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define relationships
    
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    
    // Scope for filtering by status
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }
    
    // Accessor for formatted created date
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('M d, Y H:i');
    }

    public function statusHistories()
    {
    return $this->hasMany(ComplaintStatusHistory::class);
    }
}
