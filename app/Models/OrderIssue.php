<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'type',
        'description',
        'photo_path',
        'video_url',
        'contact_phone',
        'status',
        'reported_at',
        'response_due_at',
        'resolution_due_at',
        'first_responded_at',
        'resolved_at',
        'sla_status',
        'admin_response',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'response_due_at' => 'datetime',
        'resolution_due_at' => 'datetime',
        'first_responded_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (OrderIssue $issue) {
            $reportedAt = $issue->reported_at ?? now();

            $issue->reported_at = $reportedAt;
            $issue->response_due_at = $issue->response_due_at ?? $reportedAt->copy()->addHours(config('service.complaint_sla.response_hours', 4));
            $issue->resolution_due_at = $issue->resolution_due_at ?? $reportedAt->copy()->addHours(config('service.complaint_sla.resolution_hours', 48));
            $issue->sla_status = $issue->sla_status ?: 'on_track';
        });
    }

    public function refreshSlaStatus(): string
    {
        $now = now();

        if ($this->first_responded_at && $this->response_due_at && $this->first_responded_at->gt($this->response_due_at)) {
            return 'breached';
        }

        if (!$this->first_responded_at && $this->response_due_at && $now->gt($this->response_due_at)) {
            return 'breached';
        }

        if ($this->resolved_at && $this->resolution_due_at && $this->resolved_at->gt($this->resolution_due_at)) {
            return 'breached';
        }

        if (!in_array($this->status, ['resolved', 'rejected'], true) && $this->resolution_due_at && $now->gt($this->resolution_due_at)) {
            return 'breached';
        }

        if (in_array($this->status, ['resolved', 'rejected'], true)) {
            return 'met';
        }

        $warningAt = $now->copy()->addHours(config('service.complaint_sla.warning_hours', 2));
        if ((!$this->first_responded_at && $this->response_due_at && $this->response_due_at->lte($warningAt)) ||
            ($this->resolution_due_at && $this->resolution_due_at->lte($warningAt))) {
            return 'at_risk';
        }

        return 'on_track';
    }

    public function syncSlaStatus(): void
    {
        $status = $this->refreshSlaStatus();

        if ($this->sla_status !== $status) {
            $this->forceFill(['sla_status' => $status])->save();
        }
    }

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
