<?php

namespace App\Helpers;

trait ErrorHelpers
{
    public function notFound($url)
    {
        return view('main::ErrorPages.404', compact('url'));
    }
}