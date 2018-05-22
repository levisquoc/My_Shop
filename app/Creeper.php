<?php

namespace App;

use App\Helpers\ErrorHelpers;
use App\Modules\SiteSetting\Models\Setting;
use Auth;
use Carbon\Carbon;
use Harimayco\Menu\Models\MenuItems;
use Harimayco\Menu\Models\Menus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Config;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;


class Creeper
{
    use ErrorHelpers;

    private $life_time;

    public function __construct()
    {
        $this->life_time = Carbon::now()->addMonth();
    }

    public function canOrFail($permission)
    {
        if (!Auth::guard('admin')->user()->hasRole('superadministrator') && !Auth::guard('admin')->user()->can($permission)) {

            throw new UnauthorizedHttpException(null);
        }
        return true;
    }

    public function canOrabort($permission)
    {
        if (!Auth::guard('admin')->user()->hasRole('superadministrator') && !Auth::guard('admin')->user()->can($permission)) {

            return false;
        }
        return true;
    }

    public function setting($key)
    {
        $setting = Cache::remember('setting-' . $key, $this->life_time, function () use ($key) {

            return Setting::select('value')->where('key', $key)->first();
        });


        return $setting->value;
    }

    public function menu($key)
    {
        $menu = Cache::remember('menu-' . $key, $this->life_time, function () use ($key) {
            return Menus::where('name', $key)->first();
        });
        if (isset($menu)) {
            $menu_item = new MenuItems;
            $menu_item = Cache::remember('menu-item-' . $key, $this->life_time, function () use ($menu_item, $menu) {
                return $menu_item->getall($menu->id)->where('parent', 0);
            });
            return $menu_item;
        }
        return false;
    }

    /**
     * @param        $model use to collect
     * @param        $status
     * @param null   $limit number record
     * @param bool   $inRandomOrder get random record
     * @param string $order order by field
     * @param string $order_type
     *
     * @return mixed
     */

    public function getList(
        $model,
        $name,
        $status,
        $limit = null,
        $inRandomOrder = false,
        $order = 'created_at',
        $order_type = 'desc'
    ) {

        $model_name = collect(explode('\\', $model))->last();

        $this->life_time = Carbon::now()->addHours(2);

        $data = Cache::remember('getList_' . $model_name . '_' . $name, $this->life_time,
            function () use ($model, $status, $limit, $inRandomOrder, $order, $order_type) {
                $data = $model::where('status', $status);

                if ($inRandomOrder) {
                    $data = $data->inRandomOrder();
                }

                // Take limit records
                if (isset($limit)) {
                    $data = $data->take($limit);
                }

                return $data->orderBy($order, $order_type)->get();
            });

        return $data;
    }

    /**
     * @param        $model
     * @param        $target for not select this item
     * @param        $status
     * @param        $related
     * @param null   $limit
     * @param bool   $inRandomOrder
     * @param string $order
     * @param string $order_type
     *
     * @return mixed
     */

    public function getListRelated(
        $model,
        $target,
        $status,
        $related,
        $limit = null,
        $inRandomOrder = false,
        $order = 'created_at',
        $order_type = 'desc'
    ) {
        $related_model_name = collect(explode('\\', $related['model']))->last();
        $model_name = collect(explode('\\', $model))->last();

        $related_id = Cache::remember('related_id_' . $related_model_name . '_' . $related['slug'], $this->life_time,
            function () use ($related) {
                return $related['model']::select('id')->where('slug', $related['slug'])->first()->id;
            });

        $data = Cache::remember('getListRelated_' . $model_name . '_' . $target, $this->life_time, function () use (
            $related_id,
            $model,
            $model_name,
            $target,
            $status,
            $limit,
            $inRandomOrder,
            $order,
            $order_type
        ) {
            $data = $model::where('slug', '!=', $target)->where('category_id', $related_id)->where('status', $status);

            if ($inRandomOrder) {
                $data = $data->inRandomOrder();
            }

            // Take limit records
            if (isset($limit)) {
                $data = $data->take($limit);
            }

            return $data->orderBy($order, $order_type)->get();
        });

        return $data;
    }

    /**
     *
     * Get link and push session for navigation route
     *
     * @param $link
     *
     * @return mixed
     *
     */

    public function getLink($link)
    {
        $link = explode('/', $link);

        $lifetime = Carbon::now()->addMonth();

        Cache::put($link[1], $link[0], $lifetime);

        return $link[1];

    }
}