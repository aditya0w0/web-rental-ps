<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleReaction extends Model
{
    use HasFactory;

    public const TYPE_LIKE = 'like';
    public const TYPE_HELPFUL = 'helpful';
    public const TYPE_LOVE = 'love';

    public const TYPES = [
        self::TYPE_LIKE,
        self::TYPE_HELPFUL,
        self::TYPE_LOVE,
    ];

    public const LABELS = [
        self::TYPE_LIKE => 'Like',
        self::TYPE_HELPFUL => 'Helpful',
        self::TYPE_LOVE => 'Love',
    ];

    public const ICONS = [
        self::TYPE_LIKE => 'fa-thumbs-up',
        self::TYPE_HELPFUL => 'fa-lightbulb',
        self::TYPE_LOVE => 'fa-heart',
    ];

    protected $fillable = [
        'article_id',
        'user_id',
        'guest_token_hash',
        'type',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
