<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    protected $visible = [
        'name',
    ];

    /**
     * リレーションシップ - photosテーブル
     * @return HasMany
     */
    public function photos()
    {
        return $this->hasMany('App\Photo');
    }
}
