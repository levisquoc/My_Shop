<?php

namespace App\Http\Middleware;

use App\Modules\Widgets\Models\Widget;
use App\Translator;
use Closure;

class WidgetMiddleware
{
    use Translator;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if (!method_exists($response, 'content')):
            return $response;
        endif;
        $content = $response->content();
        $widgets = Widget::all();
        foreach ($widgets as $key => $value) {
            $content = str_replace($value->slug, $value->language('content'), $content);

            foreach ($widgets as $value2) {
                $content = str_replace($value2->slug, $value2->language('content'), $content);

                foreach ($widgets as $value3) {
                    $content = str_replace($value3->slug, $value3->language('content'), $content);

                    foreach ($widgets as $value4) {
                        $content = str_replace($value4->slug, $value4->language('content'), $content);
                    }
                }
            }
            //$response->setContent($content);
        }
        $content = str_replace('[email-subcrible]', view('components.email-subcrible')->render(), $content);
        //$response->setContent($content);

        $content = str_replace('[contact]', view('components.contact')->render(), $content);
        $response->setContent($content);
        return $response;
    }
}
