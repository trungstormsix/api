<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;
use App\User;
class LoginController extends Controller
{

	public function __construct(){
            
	}

    public function login(Request $request){
        
    	$this->validate($request, [
    		'email' => 'required',
    		'password' => 'required'
    	]);
        
        $user = User::where("username", request("username"))->orWhere("email", request("email"))->first();
        if(!$user) {             
            $this->validate($request, [
    		'email' => 'required',
    		'username' => 'required',
    		'password' => 'required'
            ]);
            
            $user = User::create([
    		'username' => request('username'),
    		'name' => request('name'),
    		'email' => request('email'),
    		'password' => bcrypt(request('password'))
            ]);
           
            $user->roles()->sync([3]);

        }
        if(request("type") == "facebook"){
            
            $user = User::where("email", request("email"))->first();          
        }else if(!Auth::attempt(['email'=>request('email'),'password'=>request('password')])){
             return response()->json(["success" => FALSE, "message" => "Login Failed!"], 200);
        }else{
            $user = Auth::user();
        }
        if($user->api_token == null){
            $api_token = str_random(60);
            while(User::where("api_token", $api_token)->first()){
                $api_token = str_random(60);
            }
            $user->api_token = $api_token;
            $user->save();
        }
          
        $data = new \stdClass();
        $data->success = true;
        $data->api_token = $user->api_token;
        $data->name = $user->name ? : $user->username;
        $data->user_id = $user->id;
       
        return response()->json($data, 200);
        
    }

    public function logout(Request $request){    	 

    	return response()->json([], 204);

    }
}
