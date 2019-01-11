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

5. Update users table add 'status' field type Boolean and default as active or value is equal to zero / 0

```
  $table->boolean('status')->default(1);
```

6. Make UserResource and update based to your usage, if has existing. Do not recreate and used it.

```
php artisan make:resource UserResource
```

7. Publish config login-messages.php but include first the service provider in config app in provider set. Add this line.
```
MarkVilludo\LaravelLogin\ServiceProvider::class,
```
```
php artisan vendor:publish --provider="MarkVilludo\LaravelLogin\ServiceProvider" --tag="config"
```

## Usage - login in API
use MarkVilludo\LaravelLogin\Login;

```
    public function __construct(Login $login)
    {
      $this->login = $login;
    }

    public function login(Request $request) 
    {

      //Get email and password
      $email = $request->email;
      $password = $request->password;
      $projectName = 'My Sample Project'; //used to include in generated token using passport API.

      //use helper login user
      return $this->login->loginApi($projectName, $email, $password);
    }
```

## Usage - Login with CMS or expected to return in other views.

1. Publish login view initial page.

```
php artisan vendor:publish --provider="MarkVilludo\LaravelLogin\ServiceProvider" --tag="views"
```

2. Make new route to redirect after success login the correct credentials, and for the initial views for login page.

Route::get('/login', function () {
    return view('login');
});

Route::post('/login', 'Auth\LoginController@login')->name('web.login');
Route::resource('/dashboard','Admin\DashboardController');

3. In Controllers, in Auth\LoginController.php paste this code.
```
  public function login(Request $request) 
  {
      //Get email and password and passed to function
      $routeName = 'dashboard.index'; //used for redirect route.
      //dashboard.index - its auto generated route when you add Route::resource('dashboard'); in routes.  
      //But depends on you, you can create new route. you just need to pass the route name.

      //use helper login user
      return $this->login->loginCMS($routeName, $request->email, $request->password);
  }
  
```
