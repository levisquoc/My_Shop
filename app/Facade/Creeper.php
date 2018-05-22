<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

class Creeper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'creeper';
    }
}