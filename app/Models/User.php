<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HandlesSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Devdojo\Auth\Models\User as AuthUser;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
    // class User extends Authenticatable implements FilamentUser
    // class User extends AuthUser implements MustVerifyEmail, FilamentUser
{
    use HasFactory, Notifiable;

    use HasSlug;

    use SoftDeletes;

    use HandlesSoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'username',
        'email',
        'phone',
        'birthday',
        'gender',
        'password',
        'cover_path',
        'avatar_path',
        'pinned_post_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            // ->generateSlugsFrom(['name', 'surname'])
            ->generateSlugsFrom(['name'])
            ->saveSlugsTo('username');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    public function followings(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }

    // public function comments()
    // {
    //     return $this->hasMany(AdsComment::class);
    // }

    /**
     * Determine if the user can access the specified Filament panel.
     *
     * @param  \Filament\Panel  $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Check if the user has the `is_filament_admin` attribute set to true
        if ($panel->getId() === 'admin') {
            return $this->is_filament_admin;
        }

        return true;
    }

    // Chat
    public function groupChats()
    {
        return $this->belongsToMany(GroupChat::class, 'group_chat_participants', 'user_id', 'group_chat_id')
            ->withTimestamps();
    }

    // public function conversations()
    // {
    //     return $this->belongsToMany(Conversation::class, 'conversation_participants', 'user_id', 'conversation_id')
    //                 ->withTimestamps();
    // }
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // Kalakalkoto
    // public function items()
    // {
    //     return $this->hasMany(Item::class);
    // }

    // public function marketSentMessages()
    // {
    //     return $this->hasMany(MarketMessage::class, 'sender_id');
    // }

    // public function marketReceivedMessages()
    // {
    //     return $this->hasMany(MarketMessage::class, 'receiver_id');
    // }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'buyer_id');
    }

    public function wallet()
    {
        return $this->hasOne(UserWallet::class);
    }
}
