<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'content',
        'user_name',
        'parent_id',
        'likes',
        'is_edited',
        'dislikes',
    ];


    /**
     * Get the parent comment if this is a reply
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get replies to this comment
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Format likes count for display
     */
    public function getFormattedLikesAttribute()
    {
        return $this->formatCount($this->likes);
    }

    /**
     * Format dislikes count for display
     */
    public function getFormattedDislikesAttribute()
    {
        return $this->formatCount($this->dislikes);
    }

    /**
     * Format count for display (e.g., "1k" for 1000)
     */
    private function formatCount($count)
    {
        if ($count >= 1000000) {
            return round($count / 1000000, 1) . 'M';
        } elseif ($count >= 1000) {
            return round($count / 1000, 1) . 'K';
        }

        return $count;
    }
}
