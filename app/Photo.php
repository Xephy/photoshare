<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Photo extends Model{
    protected $keyType = 'string';
    protected $appends = [
        'url'
    ];
    protected $visible = [
        'id',
        'owner',
        'url',
        'comments',
    ];
    protected $perPage = 15;

    const ID_LENGTH = 12;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if( ! Arr::get($this->attributes, 'id'))
        {
            $this->setId();
        }
    }

    private function setId()
    {
        $this->attributes['id'] = $this->getRandomId();
    }

    /**
     * @return string
     * @throws Exception
     */
    private function getRandomId()
    {
        $characters = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'), [
                '-',
                '_'
            ]
        );

        $length = count($characters);
        $id = '';

        for($i = 0; $i < self::ID_LENGTH; $i++)
        {
            $id .= $characters[random_int(0, $length - 1)];
        }

        return $id;
    }

    /**
     * @return BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }

    public function getUrlAttribute()
    {
        return Storage::disk('public')->url($this->attributes['filename']);
    }

    /**
     * @return HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Comment')->orderBy('id', 'desc');
    }
}
