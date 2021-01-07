<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use PragmaRX\Google2FAQRCode\Google2FA;
use PragmaRX\Google2FAQRCode\QRCode\Chillerlan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        Carbon::setlocale(config('app.name'));
        Carbon::setToStringFormat('d.m.Y');

        $this->app->bind(Google2FA::class, function (): Google2FA {
            $chillerlan = new class() extends Chillerlan {
                protected $options = ['imageBase64' => false];
            };

            return new Google2FA($chillerlan);
        });
    }
}
