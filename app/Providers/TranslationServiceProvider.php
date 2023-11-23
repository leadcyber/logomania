<?php
 
namespace App\Providers;
 
use Illuminate\Translation\TranslationServiceProvider as ServiceProvider;
use App\Services\TranslationLoader; 

class TranslationServiceProvider extends ServiceProvider
{
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new TranslationLoader($app['files'], $app['path.lang']);
        });
    }
}