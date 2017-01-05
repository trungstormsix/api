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

    public function getApp($id = 0) {            
        if($id){
            $app = PromoteApp::find($id);
        }else{
            $app = new PromoteApp();            
        }
        $groups = PromoteGroup::all();
        return view('admin/promotes/editApp', ['app' => $app, 'groups' => $groups]);
    }

    /**
     * save cat
     */
    public function postApp(Request $req) {
        $this->validate($req, [
            'title' => 'required|max:255',
            'package'=> 'required|max:255',
        ]);
        if ($req->id) {
            $app = PromoteApp::find($req->id);
        } else {
            $app = new PromoteApp();
            $app->package = $req->package;
            $app->save();
        }


        $app->publish_up = date("Y-m-d H:i:s", strtotime($req->publish_up));
        $app->publish_down = $req->publish_down;
        $app->status = $req->status ? $req->status : 0;

        $result = $app->update($req->all());
        if (!$result) {
            Session::flash('error', 'App fail to save!');
            Input::flash();
        } else {
            Session::flash('success', 'App saved successfully!');
        }
        return Redirect::to('/admin/promote/app/' . $app->id);
    }

}
