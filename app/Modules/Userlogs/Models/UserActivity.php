<?php

namespace App\Modules\Userlogs\Models;

use App\Modules\Acl\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    //
    protected $table = 'useractivity';

    protected $fillable = [
        'user_id',
        'user_ip',
        'user_language',
        'action',
        'object',
        'object_id',
        'value_before',
        'value_after',
        'browser'
    ];

    public $timestamp = true;

    public function users()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }
}
