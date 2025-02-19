<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    // Allow these attributes for mass assignment
    protected $fillable = [
        'name',
        'contact_no',
        'designation',
        'school_id',
        'category_id',
        'problem',
        'priority',
        'assigned_to',
        'status',
        'assigned_by',
        
    ];

    public function designation()
{
    return $this->belongsTo(Designation::class);
}


    public function assignedToUser() {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    
    public function assignedByUser() {
        return $this->belongsTo(User::class, 'assigned_by');
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}