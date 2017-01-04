<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Role;
use App\Permission;
use App\User;
use App\library\DomParser;
use App\Models\Picvoc\Voc;
use App\Models\Picvoc\PicvocCat;
use File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PicvocController extends Controller {

  
    public function __construct() {
        
    }

    public function cats() {    
        $cats = PicvocCat::where("status",1)->where("parent_id",">",1)->orderBy("lft","DESC")->get();
        return $cats;
    }
 

}
