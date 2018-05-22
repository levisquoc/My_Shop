<?php

namespace App\Modules\SiteSetting\Http\Controllers;

use App\Facade\Creeper;
use App\Modules\SiteSetting\Http\Requests\SettingRequest;
use App\Modules\SiteSetting\Models\Setting;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use App\Helpers\UserActivityHelpers;
use App\Http\Requests;
use Auth;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    use UserActivityHelpers;

    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Creeper::canOrFail('access-settings');

        $settings = Setting::orderBy('order', 'ASC')->get();
        $types = Config('creeper.site_setting.type');
        $groups = Config('creeper.site_setting.group');
        return view('site-setting::index', compact('types', 'groups', 'settings'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SettingRequest $request)
    {
        Creeper::canOrFail('access-settings');

        $request->merge(['value' => '']);
        $value = $request->all();
        //user activity
//        $action = "Create";
//        $object = "Setting";
//        $object_id = null;
//        $value_before = null;
//        $value_after = "Value:" . $value;
//
//        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //user activity

        Setting::create($request->all());
        return redirect()->route('site_setting.settings.index')->with([
            'status_type' => 'success',
            'status' => $request->display_name . ' has been successfully created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $group)
    {
        Creeper::canOrFail('access-settings');

        $settings = Setting::where('group', $group)->get();
        if (isset($settings)) {
            foreach ($settings as $setting) {
                $key = $setting->key;
                $group_key = 'group-' . $key;
                $data['value'] = $request->$key ? $request->$key : '';
                $data['group'] = $request->$group_key ? $request->$group_key : $group;

                //user activity
//                $action = "Edit";
//                $object = "Setting";
//                $object_id = $group;
//                $value_before = null;
//                $value_after = "Name:" . $data;
//
//                UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
                //user activity


                $setting->update($data);
            }
            return redirect()->route('site_setting.settings.index')->with([
                'status_type' => 'success',
                'status' => 'Settings has been successfully updated'
            ]);
        }
        return redirect()->route('site_setting.settings.index')->with([
            'status_type' => 'error',
            'status' => 'Something wrongs!!!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Creeper::canOrFail('access-settings');

        //user activity
        $action = "Delete";
        $object = "Setting";
        $object_id = $id;
        $value_before = "Name:" . $id;
        $value_after = null;
        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity
        Setting::destroy($id);

        return redirect()->route('site_setting.settings.index')->with([
            'status_type' => 'success',
            'status' => 'Settings has been successfully deleted'
        ]);
    }

    public function delete_value($id)
    {
        Creeper::canOrFail('access-settings');

        $setting = Setting::find($id);

        if (isset($setting->id)) {

            $setting->value = '';
            $setting->save();
        }

        return redirect()->route('site_setting.settings.index')->with([
            'status_type' => 'success',
            'status' => 'Settings has been successfully updated'
        ]);
    }

    public function move_up($id)
    {
        Creeper::canOrFail('access-settings');

        $setting = Setting::find($id);

        $swapOrder = $setting->order;
        $previousSetting = Setting::where('order', '<', $swapOrder)
            ->where('group', $setting->group)
            ->orderBy('order', 'DESC')->first();
        $data = [
            'status' => 'This is already at the top of the list',
            'status_type' => 'error',
        ];

        if (isset($previousSetting->order)) {
            $setting->order = $previousSetting->order;
            $setting->save();
            $previousSetting->order = $swapOrder;
            $previousSetting->save();

            $data = [
                'status' => 'Moved ' . $setting->display_name . ' setting order up',
                'status_type' => 'success',
            ];
        }

        return back()->with($data);
    }

    public function move_down($id)
    {

        Creeper::canOrFail('access-settings');

        $setting = Setting::find($id);

        $swapOrder = $setting->order;
        $previousSetting = Setting::where('order', '>', $swapOrder)
            ->where('group', $setting->group)
            ->orderBy('order', 'ASC')->first();
        $data = [
            'status' => 'This is already at the bottom of the list',
            'status_type' => 'error',
        ];

        if (isset($previousSetting->order)) {
            $setting->order = $previousSetting->order;
            $setting->save();
            $previousSetting->order = $swapOrder;
            $previousSetting->save();

            $data = [
                'status' => 'Moved ' . $setting->display_name . ' setting order down',
                'status_type' => 'success',
            ];
        }

        return back()->with($data);
    }

    public function getSlug(Request $request)
    {

        if ($request->type) {
            $slug = SlugService::createSlug(Setting::class, 'key', $request->slug, ['unique' => false]);
        } else {
            $slug = SlugService::createSlug(Setting::class, 'key', $request->slug);
        }

        return $slug;

    }
}
