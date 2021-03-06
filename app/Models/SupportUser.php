<?php

namespace App\Models;

use App\Concerns\Models\Searchable;
use App\Permissions\HasAdminPermissionsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SupportUser extends Authenticatable implements HasMedia
{
    use HasFactory, HasAdminPermissionsTrait, InteractsWithMedia, Searchable, SoftDeletes;

    /**
     * Searchable attributes.
     *
     * @return string[]
     */
    public $searchable = [
        'name',
        'username',
        'password',
        'local_lang',
    ];

    protected $guard = 'support';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(400);
    }

    public function userSupportQuery(): HasMany
    {
        return $this->hasMany(UserSupportQuery::class, 'assigned_to', 'id');
    }
}
