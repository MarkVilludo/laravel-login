<?php

namespace MarkVilludo\LaravelLogin;

use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class Login {

    /**
       * Help to easily login user not reinvent the wheel always when making new laravel app.
       *
       * @param string $email
       * @param string $password
       * @return response
    */
    function loginApi($projectName, $email, $password)
    {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            //Check if account is active
            if (auth()->user()->status) {
                $user = auth()->user();
            
                $data['token'] =  $user->createToken($projectName)->accessToken;
                $data['user'] =  new UserResource($user);

                return response()->json(['success' => $data], 200);
            } else {
                //when deactivated account
                return response()->json([
                        'success' => false,
                        'message' => config('login-messages.AccoundDeactivated')
                    ], 200);
            }
        } else {
            return response()->json(['error'=> config('login-messages.InvalidEmailPassword')], 401);
        }
    }

    /**
       * Login tru CMS
       *
       * @param string $email
       * @param string $password
       * @return response
    */

    function loginCMS($routeName, $email, $password)
    {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {

            if (auth()->user()->status) {
                $data['message'] = 'Login successful';
                return redirect()->route($routeName);
                
            } else {
                $message = 'Account deactivated, Please contact system administrator.';
                session()->flash('message', $message);
                return redirect()->back();
            }
        } else {
            $message = 'Invalid credentials';
            session()->flash('message', $message);
            return redirect()->back();
        }
    }

}


