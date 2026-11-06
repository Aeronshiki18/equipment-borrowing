<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    use HasFactory;

    // Added the new fields here
    protected $fillable = [
        'user_id', 
        'equipment_id', 
        'quantity', 
        'status',
        'purpose',
        'borrow_date',
        'due_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}