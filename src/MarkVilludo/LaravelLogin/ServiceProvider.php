<?php 

namespace MarkVilludo\LaravelLogin;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use MarkVilludo\LaravelLogin\LaravelLogin;

class ServiceProvider extends BaseServiceProvider {
    
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {      
        $this->publishes([
            __DIR__.'/config/login-messages.php' => $this->app->configPath().'/'.'login-messages',
        ], 'config');


       $this->publishes([
           __DIR__.'/views/login.blade.php' => resource_path('/views'),
        ],'views');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
      // require_once($filename);
    }

}
