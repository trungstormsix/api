<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\User;

class ApiLoginController extends Controller {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
    }

    public function login(Request $request) {
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            return response()->json([
                        'auth' => true,
                        'isAdmin' => $user->hasRole("admin") ? true : false,
                        'intended' => "loged in before!"
            ]);
        }
        $credentials = $request->only('username', 'password');
        if (\Illuminate\Support\Facades\Auth::attempt($credentials, $request->has('remember'))) {
            $user = \Illuminate\Support\Facades\Auth::user();
            return response()->json([
                        'auth' => true,
                        'isAdmin' => $user->hasRole("admin") ? true : false,
                        'intended' => \Illuminate\Support\Facades\URL::previous()
            ]);
        }

        return response()->json([
                    'auth' => false,
                    'intended' => URL::previous()
        ]);
    }

    public function createUser() {
        $usename = Input::get('username');
        $email = Input::get('email');
        $password = Input::get('password');

        $result = new \stdClass();
        $result->result = false;
        $result->message = "Something wrong, please check your all inputs!";
        if ($usename && $email && $password && strlen($usename) > 5 && strlen($email) > 10) {
            $user = User::where("username", $usename)->first();
            if ($user) {
                $result->message = "Username exist!";
                return \Illuminate\Support\Facades\Response::json($result);
            }
            $user = User::where("email", $email)->first();
            if ($user) {
                $result->message = "This email is registered!";
                return \Illuminate\Support\Facades\Response::json($result);
            }
        } else {
            return \Illuminate\Support\Facades\Response::json($result);
        }
        $user = new User();
        $user->username = $usename;
        $user->email = $email;
        $user->password = bcrypt($password);

        $user->save();
        //save roles
        $user->roles()->sync([3]);
        $result->result = true;
        $result->message = "User created successfully";

        return \Illuminate\Support\Facades\Response::json($result);
    }

}
