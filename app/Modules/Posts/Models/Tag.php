<?php

namespace App\Modules\Posts\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use Sluggable;
    use SoftDeletes;

    protected $table = 'tags';

    protected $fillable = [
        'name',
        'slug',
        'status',
        'pos'
    ];

    public $timestamp = true;


    protected $dates = ['deleted_at'];

    public function post()
    {
        return $this->belongsToMany(Post::class, 'posts_tags', 'tags_id', 'posts_id');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
