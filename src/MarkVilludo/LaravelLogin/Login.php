<?php

namespace MarkVilludo\LaravelLogin;

use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Laravel\Passport\Passport;
use Illuminate\Http\Request;

class Login {

    if (!function_exists('loginApi')) {
        /**
         * Help to easily login user not reinvent the wheel always when making new laravel app.
         *
         * @param string $email
         * @param string $password
         * @return response
         */
        function loginApi($email, $email)
        {
          // return $request->all();
          if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
              //Check if account is active
              if (auth()->user()->status) {
                  $user = auth()->user();
              
                  $data['token'] =  $user->createToken('Pr')->accessToken;
                  $data['user'] =  new UserResource($user);

                  return response()->json(['success' => $data], $this->successStatus);
              } else {
                  //when deactivated account
                  return response()->json([
                          'success' => false,
                          'message' => config('app_messages.AccoundDeactivated')
                      ], $this->successStatus);
              }
          } else {
              return response()->json(['error'=> config('app_messages.InvalidEmailPassword')], 401);
          }
        }
    }

}


