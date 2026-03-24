<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintStatusHistory extends Model
{
    public $timestamps = false; // table only has created_at

    protected $fillable = [
        'complaint_id',
        'status',
        'updated_by',
        'comments',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}