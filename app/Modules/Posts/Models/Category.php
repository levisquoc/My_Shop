<?php

namespace App\Modules\Posts\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use Sluggable;
    use SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'parent_id',
        'slug',
        'status',
        'pos',
    ];
    public $timestamp = true;

    protected $dates = ['deleted_at'];

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id', 'id')->latest('updated_at');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function child()
    {
        return $this->hasMany(self::class, 'id', 'parent_id');
    }

    public function rss()
    {
        return $this->hasMany(rss_links::class, 'cate_id', 'id');
    }

    public function scopeGetWithStatus($query, $status)
    {
        return $query->where('status', $status)->get();
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
