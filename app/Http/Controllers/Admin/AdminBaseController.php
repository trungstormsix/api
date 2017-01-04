<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;


class AdminBaseController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('role:admin|email:trung@gmail.com');        
    }
 
}
