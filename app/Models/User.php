<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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

// class User extends Authenticatable implements MustVerifyEmail
class User extends AuthUser implements MustVerifyEmail, FilamentUser
{
    use HasFactory, Notifiable;

    use HasSlug;

    use SoftDeletes;

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

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function comments()
    {
        return $this->hasMany(AdsComment::class);
    }

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
}
