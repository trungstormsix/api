<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\User;
use App\Models\PromoteGroup;
use App\Models\PromoteApp;
use File;
use Illuminate\Support\Facades\Session;

class PromoteController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       
        $groups = PromoteGroup::all();

        return view('admin/promotes/home', ['groups' => $groups]);

    }
    
    public function getApp($id){
        
        $app = PromoteApp::find($id);
        $app->title = $app->title;
        $groups = PromoteGroup::all();
        return view('admin/promotes/editApp', ['app' => $app,'groups' => $groups]);

    }
    
    /**
     * save cat
     */
    public function postApp(Request $req) {
        
        if ($req->id) {
            $app = PromoteApp::find($req->id);
        } else {
            $app = new PromoteApp();
        }
         
        if ($req->title) {
            $app->title = $req->title;
            $app->publish_up = date("Y-m-d H:i:s",strtotime($req->publish_up));
            
            $app->publish_down = $req->publish_down;
            $app->group_id = $req->group_id;
            if ($req->status == 'on') {
                $app->status = 1;
            } else {
                $app->status = 0;
            }
           
            $app->save();            
        }
        Input::flash();
       return Redirect::to('/admin/promote/app/'.$app->id);
    }
 
}
