<?php

namespace App\Modules\Ads\Models;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    protected $fillable = [
        'title',
        'image',
        'link',
        'position',
        'publish_date',
        'expiration_date',
        'pos',
        'status'
    ];

    public $timestamp = true;
}
