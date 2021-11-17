<?php

namespace Canvas\Models;

use Canvas\Canvas;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use SoftDeletes;

    /**
     * Role identifier for a Contributor.
     *
     * @const int
     */
    public const CONTRIBUTOR = 1;

    /**
     * Role identifier for an Editor.
     *
     * @const int
     */
    public const EDITOR = 2;

    /**
     * Role identifier for an Admin.
     *
     * @const int
     */
    public const ADMIN = 3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'canvas_users';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be casted.
     *
     * @var array
     */
    protected $casts = [
        'digest' => 'boolean',
        'dark_mode' => 'boolean',
        'role' => 'int',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'default_avatar',
        'default_locale',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 10;

    /**
     * Get the posts relationship.
     *
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the tags relationship.
     *
     * @return HasMany
     */
    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    /**
     * Get the topics relationship.
     *
     * @return HasMany
     */
    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * Check to see if the user is a Contributor.
     *
     * @return bool
     */
    public function getIsContributorAttribute(): bool
    {
        return $this->role === self::CONTRIBUTOR;
    }

    /**
     * Check to see if the user is an Editor.
     *
     * @return bool
     */
    public function getIsEditorAttribute(): bool
    {
        return $this->role === self::EDITOR;
    }

    /**
     * Check to see if the user is an Admin.
     *
     * @return bool
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->role === self::ADMIN;
    }

    /**
     * Return a default user avatar.
     *
     * @return string
     */
    public function getDefaultAvatarAttribute(): string
    {
        return Canvas::gravatar($this->email ?? '');
    }

    /**
     * Return a default user locale.
     *
     * @return string
     */
    public function getDefaultLocaleAttribute(): string
    {
        return config('app.locale');
    }
}
