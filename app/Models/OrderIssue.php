<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id','user_id','type','description','photo_path','video_url','contact_phone','status','admin_response'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(OrderIssuePhoto::class, 'order_issue_id');
    }
}