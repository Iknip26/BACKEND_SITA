<?php

namespace App\Providers;

use App\Http\Controllers\PdfParserController;
use App\Services\PdfParserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(PdfParserService::class, function ($app) {
            return new PdfParserService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
