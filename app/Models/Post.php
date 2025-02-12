<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * Class Post
 *
 * @author  Christian Aguas <acdeocera.bb88@gmail.com>
 * @property Group $group
 * @package App\Models
 *
 */

class Post extends Model
{
    use SoftDeletes, HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'body',
        'group_id',
        'shared_post_id',
        'preview',
        'preview_url'
    ];

    // With casting
    protected $casts = [
        'preview' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PostAttachment::class, 'post_id')->latest();
    }

    // public function reactions(): HasMany
    public function reactions(): MorphMany
    {
        // return $this->hasMany(PostReaction::class);
        return $this->morphMany(Reaction::class, 'object');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function latest5Comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function sharedPost()
    {
        return $this->belongsTo(Post::class, 'shared_post_id')->with('attachments')->withTrashed();
    }
    // public function sharedPost()
    // {
    //     return $this->belongsTo(Post::class, 'shared_post_id');
    // }

    public function shares()
    {
        return $this->hasMany(Post::class, 'shared_post_id');
    }

    public static function postsForTimeline($userId, $getLatest = true): Builder
    {
        $query = Post::query() // SELECT * FROM posts
            ->withCount('reactions') // SELECT COUNT(*) from reactions
            ->withCount('shares')
            ->with([
                'user',
                'group',
                'group.currentUserGroup',
                'attachments',
                'sharedPost' => function ($query) {
                    $query->with(['user', 'attachments', 'reactions']);
                },
                'comments' => function ($query) {
                    $query->withCount('reactions'); // SELECT * FROM comments WHERE post_id IN (1, 2, 3...)
                    // SELECT COUNT(*) from reactions
                },
                'comments.user',
                'comments.reactions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId); // SELECT * from reactions WHERE user_id = ?
                },

                'reactions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId); // SELECT * from reactions WHERE user_id = ?
                }
            ]);
        if ($getLatest) {
            $query->latest();
        }

        return $query;
    }

    public function isOwner($userId)
    {
        return $this->user_id == $userId;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            $post->deleted_by = auth()->id();
            $post->save();
        });
    }
}
