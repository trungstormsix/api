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
class AdminController extends AdminBaseController {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin/home');
    }
    
    public function lookedUp(){
        $words = CommonWord::orderBy('count', 'desc')->paginate(30);
        return view('admin/lookedup', compact("words"));
    }
 	 
}
