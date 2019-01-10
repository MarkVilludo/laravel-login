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
        
        // $this->mergeConfigFrom(
        //     __DIR__ . '/../../config/imageUpload.php', 'imageUpload'
        // );
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
