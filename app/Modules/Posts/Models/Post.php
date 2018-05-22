<?php

namespace App\Modules\Posts\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Acl\Models\Admin;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Post extends Model
{
    use Sluggable;
    use SoftDeletes;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'slug',
        'image',
        'content',
        'description',
        'category_id',
        'author_id',
        'status',
        'pos',
        'seo_title',
        'seo_description',
        'seo_keyword'
    ];

    public $timestamp = true;

    protected $dates = ['deleted_at'];

    public function save(array $options = [])
    {
        // If no author has been assigned, assign the current user's id as the author of the post
        if (!$this->author_id && Auth::guard('admin')->user()) {
            $this->author_id = Auth::guard('admin')->user()->id;
        }
        // If no image has been assigned, assign the default image as the image of the post
        if (!$this->image) {
            $this->image = 'Modules/Post/images/default.jpg';
        }

        parent::save();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(Admin::class, 'author_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'posts_tags', 'posts_id', 'tags_id');
    }

    public function scopeGetWithStatus($query, $status)
    {
        return $query->where('status', $status);
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
                'source' => 'title'
            ]
        ];
    }
}
