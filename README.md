# laravel-login
Laravel package API and in CMS login.

## Installation

Require this package with composer.

```shell
//Passport requires league/oauth2-server which requires defuse/php-encryption hence the issues.
//See https://github.com/paragonie/random_compat/issues/147

composer require paragonie/random_compat:2.*
composer require markvilludo/laravel-login

```

##Setup Laravel Passport Configs.

1. After successfully install package, open config/app.php file and add service provider.

```
'providers' => [

  ....

  Laravel\Passport\PassportServiceProvider::class,

],

``` 
2. Run Migration and Install
```
php artisan migrate
```

Next, we need to install passport using command, Using passport:install command, it will create token keys for security. So let's run bellow command:
```
php artisan passport:install
```

3. [Passport Configuration] In this step, we have to configuration on three place model, serviceprovider and auth config file. So you have to just following change on that file.

In model we added HasApiTokens class of Passport,

In AuthServiceProvider we added "Passport::routes()",

In auth.php, we added api auth configuration.

app/User.php

```
<?php

namespace App;


use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}

```

app/Providers/AuthServiceProvider.php

```
<?php


namespace App\Providers;


use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];


    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
    }
}
```
config/auth.php
```
return [
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
    ],

```

4. Create API Route
In this step, we will create api routes. Laravel provide api.php file for write web services route. So, let's add new route on that file.

routes/api.php

```
<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which

 
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('login', 'Api\UserController@login');
Route::post('register', 'Api\UserController@register');


Route::group(['middleware' => 'auth:api'], function(){
  Route::post('details', 'Api\UserController@details');
});


```

## Usage - login in API
```

  //Get email and password
  $email = $request->email;
  $password = $request->password;
  $projectName = 'My Sample Project'; //used to include in generated token using passport API.
  
  //use helper login user
  LoginHelper::loginApi($pr ojectName, $email, $password);
```

## Usage - Login with CMS or expected to return in other views.
```
  /Get email and password
  $email = $request->email;
  $password = $request->password;
  $route = 'campaigns.index'; //alias or name in route. ex Route::get('','')->name('campains.index');
  
  //use helper login user
  LoginHelper::loginCMS($email, $password, $route);
  
```
