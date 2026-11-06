<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipment';

    // Added 'image' here
    protected $fillable = [
        'name', 
        'description',
        'category_id', 
        'stock', 
        'status',
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class);
    }
}