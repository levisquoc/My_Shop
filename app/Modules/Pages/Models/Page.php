<?php

namespace App\Modules\Pages\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Translator;
use Auth;

class Page extends Model
{
    use Sluggable, Translator;

    protected $table = 'pages';

    protected $fillable = [
        'name',
        'slug',
        'image',
        'content',
        'description',
        'status',
        'pos',
        'seo_title',
        'seo_description',
        'seo_keyword'
    ];

    public $timestamp = true;

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
