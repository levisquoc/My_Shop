<?php

namespace App\Helpers;

use Jenssegers\Agent\Agent;
use App\Modules\Userlogs\Models\UserActivity;
use Auth;

trait UserActivityHelpers
{
    public static function saveUserActivity($action, $object, $object_id, $value_before, $value_after)
    {
        $agent = new Agent();

        $data['user_id'] = Auth::guard('admin')->user()->id;
        $data['user_ip'] = request()->ip();
        $data['action'] = $action;
        $data['object'] = $object;
        $data['object_id'] = $object_id;
        $data['value_before'] = $value_before;
        $data['value_after'] = $value_after;
        $data['browser'] = $agent->browser();
        //dd($data);
        UserActivity::firstorCreate($data);
    }
}