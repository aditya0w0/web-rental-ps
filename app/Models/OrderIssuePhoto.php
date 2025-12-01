<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderIssuePhoto extends Model
{
    use HasFactory;

    protected $fillable = ['order_issue_id','path'];

    public function issue()
    {
        return $this->belongsTo(OrderIssue::class, 'order_issue_id');
    }
}