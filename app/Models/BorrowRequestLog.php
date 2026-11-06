<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BorrowRequestLog extends Model {
    protected $fillable = ['borrow_request_id','user_id','action','note'];
    public function request() { return $this->belongsTo(BorrowRequest::class, 'borrow_request_id'); }
    public function user() { return $this->belongsTo(User::class); }
}