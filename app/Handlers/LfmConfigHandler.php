<?php

namespace App\Handlers;

use App\Facade\Creeper;

class LfmConfigHandler extends \Unisharp\Laravelfilemanager\Handlers\ConfigHandler
{
    public function userField()
    {
        if (!Creeper::canOrabort('browse-galleries')) {
            return auth()->guard('admin')->user()->name;
        }

        if (auth()->guard('web')->user()) {
            return auth()->user()->name;
        }
    }
}
