<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'author_name',
        'image',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(ArticleComment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->comments()->where('status', ArticleComment::STATUS_APPROVED);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(ArticleReaction::class);
    }
}
