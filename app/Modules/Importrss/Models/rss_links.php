<?php

namespace App\Modules\Importrss\Models;

use App\Modules\Posts\Models\Category;
use Illuminate\Database\Eloquent\Model;


class rss_links extends Model
{
    protected $table = 'rss_links';
    protected $fillable = [
        'rss_link',
        'cate_id',
        'page_target',
    ];

    public $timestamp = true;

    public function category()
    {
        return $this->belongsTo(Category::class, 'cate_id', 'id');
    }
}
