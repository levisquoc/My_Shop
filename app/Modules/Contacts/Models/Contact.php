<?php

namespace App\Modules\Contacts\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message'
    ];

    public $timestamp = true;

}
