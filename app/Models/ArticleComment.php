<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleComment extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_FLAGGED = 'flagged';
    public const STATUS_DELETED = 'deleted';

    protected $fillable = [
        'article_id',
        'user_id',
        'guest_name',
        'guest_email',
        'body',
        'status',
        'moderation_note',
        'moderated_by',
        'moderated_at',
        'approved_at',
        'ip_hash',
        'user_agent',
    ];

    protected $casts = [
        'moderated_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeNeedsModeration(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_FLAGGED]);
    }

    public function displayName(): string
    {
        return $this->user?->name ?: ($this->guest_name ?: 'Guest');
    }

    public function approve(User $moderator): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'moderated_by' => $moderator->id,
            'moderated_at' => now(),
            'approved_at' => $this->approved_at ?: now(),
        ]);
    }

    public function flag(User $moderator, ?string $note = null): void
    {
        $this->update([
            'status' => self::STATUS_FLAGGED,
            'moderation_note' => $note,
            'moderated_by' => $moderator->id,
            'moderated_at' => now(),
        ]);
    }

    public function deleteWithTrace(User $moderator, ?string $note = null): void
    {
        $this->update([
            'status' => self::STATUS_DELETED,
            'moderation_note' => $note,
            'moderated_by' => $moderator->id,
            'moderated_at' => now(),
        ]);
    }
}
