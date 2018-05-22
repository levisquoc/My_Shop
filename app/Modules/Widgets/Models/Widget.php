<?php

namespace App\Modules\Widgets\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Translator;

class Widget extends Model
{
    use Sluggable, Translator;

    protected $fillable = [
        'name',
        'slug',
        'content',
        'status',
        'pos',
    ];

    public $timestamp = true;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
