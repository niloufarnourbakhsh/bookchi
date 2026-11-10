<?php

namespace App\Providers;

use App\ApiResponse\ApiResponseBuilder;
use Illuminate\Support\ServiceProvider;

class ApiResponseProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('ApiResponse' , function (){
            return new ApiResponseBuilder();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
