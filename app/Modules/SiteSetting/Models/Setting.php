<?php

namespace App\Modules\SiteSetting\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use Sluggable;

    protected $fillable = [
        'key',
        'display_name',
        'value',
        'details',
        'type',
        'order',
        'group'
    ];

    public $timestamps = false;

    public function save(array $options = [])
    {

        if (!$this->order != null) {
            $this->order = self::where('group', $this->group)->max('order') + 1;
        }

        parent::save();
    }

    public function sluggable()
    {
        return [
            'key' => [
                'source' => 'display_name'
            ]
        ];
    }
}
