<?php

namespace App\Providers;

use App\Http\ViewComposers\Error500ViewComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('errors::500', Error500ViewComposer::class);
    }
}
