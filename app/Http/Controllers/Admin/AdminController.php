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
use App\Models\Playlist;
use App\Models\Video;
use App\Models\Ycat;
use File;
 
use Illuminate\Support\Facades\Session;
use App\Models\CommonWord;
use App\Models\Picvoc\PicvocMean;
use App\Models\PromoteApp;

class AdminController extends AdminBaseController {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
        $reports = \App\Models\ListeningReport::where("status", 0)->orderBy("updated", "DESC")->paginate(30);
        $means = PicvocMean::orderBy("updated","desc")->take(30)->get();
        $published_apps = PromoteApp::where("status", 1)->where("publish_up", "<", date("Y-m-d H:i:s"))->where("publish_down", ">", date("Y-m-d H:i:s"))->get();
 
        return view('admin/home', compact('reports','means','published_apps'));
    }
    
    public function lookedUp(){
        $words = CommonWord::orderBy('count', 'desc')->paginate(30);
        
        return view('admin/lookedup', compact("words"));
    }
 	 
}
