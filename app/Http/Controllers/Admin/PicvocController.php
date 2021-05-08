<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Role;
use App\Permission;
use App\User;
use App\library\DomParser;
use App\Models\Picvoc\Voc;
use App\Models\Picvoc\PicvocCat;
use App\Models\Picvoc\PicvocMean;

use File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\CommonWord;
use App\library\CropPicVoc;
use Illuminate\Support\Facades\DB;

class PicvocController extends Controller {

    var $url = 'http://idioms.thefreedictionary.com/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    public function searchCat() {
        $term = Input::get('term', '0');
        $cats = PicvocCat::where("title", 'like', "%$term%")->where("parent_id", ">", 1)->get();

        $return = [];
        foreach ($cats as $cat) {
            $return[] = ['key' => $cat->id, 'label' =>$cat->title, 'value' => $cat->title];
        }
        return $return;
    }

    public function delete() {
        $voc_id = Input::get('voc_id', '0');
        $cat_id = Input::get('cat_id', '0');
        if ($voc_id && $cat_id) {
            $cat = PicvocCat::find($cat_id);
            $cat->vocs()->detach($voc_id);
        }		
		$result = new \stdClass();
		$result->status = true;
		return response()->json($result);
    }

    public function add() {
        $voc_id = Input::get('voc_id', '0');
        $cat_id = Input::get('cat_id', '0');
        if ($voc_id && $cat_id) {
            $cat = PicvocCat::find($cat_id);
            $result = $cat->vocs()->syncWithoutDetaching([$voc_id]);
            return $result;
        }
    }
    
    public function cats(){
        $cats = PicvocCat::where("parent_id",">",1)->orderBy("ordering","asc")->get();
         if (@$_GET['sort_by']) {
            Session::put('pvc_sort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('pvc_sort_dimen', $dimen);
        }
        $sort_by = Session::get('pvc_sort_by', 'liked');
        $sort_dimen  = Session::get('pvc_sort_dimen', 'desc');
         
        return view('admin/picvoc/cats', compact('cats','sort_by','sort_dimen'));
    }
    public function cat($cat_id){
        if((int)$cat_id){
            $cat = PicvocCat::find($cat_id); 
            $img_data = null;
            if($cat->img && file_exists("../api/image/".$cat->img)){
                $img_data = getimagesize("../api/image/".$cat->img);
            }
        }   else{
            $img_data = null;
            $cat = null;
        }
        $parents = PicvocCat::where("parent_id",1)->get();
//        dd($parents);
//        dd($img_data);
        return view('admin/picvoc/cat', compact('cat',"img_data", 'parents'));
    }
    public function postVoc(Request $req){
        $voc_id = $req->id;
        
        $voc = Voc::find($voc_id);
        $voc->status = $req->status ? 1 : 0;
        $voc->en_us = trim($req->en_us);
        $voc->en_us_type = trim($req->en_us_type);
        $voc->en_us_pr = trim($req->en_us_pr);
        $voc->en_uk_pr = trim($req->en_uk_pr);
        $voc->en_us_mean = trim($req->en_us_mean);
        $voc->en_us_ex = trim($req->en_us_ex);
        if($req->en_us_mp3_link){
            $mp3_en_us_file = \App\library\OcoderHelper::getFileName($req->en_us_mp3_link);
            if($mp3_en_us_file){
                $status_us = Storage::disk('picvoc_audios')->put($mp3_en_us_file, file_get_contents($req->en_us_mp3_link));
                if($status_us){
                   $voc->en_us_audio = $mp3_en_us_file;
                }
            }           
        }
        if($req->en_uk_mp3_link){
            $mp3_en_uk_file = \App\library\OcoderHelper::getFileName($req->en_uk_mp3_link);
            if($mp3_en_uk_file){
                $status_uk = Storage::disk('picvoc_audios')->put($mp3_en_uk_file, file_get_contents($req->en_uk_mp3_link));
                if($status_uk){
                   $voc->en_uk_audio = $mp3_en_uk_file;
                }
            }           
        }
        $cat_ids = $req->cat_ids;
        if($cat_ids){
            $voc->cats()->sync($cat_ids);
        }else{
            $voc->cats()->sync([]);
        }
        $voc->save();
        return Redirect::to('/admin/picvoc/voc/'.$voc->id);
    }

    public function postCat(Request $req) {
        Session::flash('success', 'PicVoc Category is saved successfully!');
        $cat_id = $req->id;
        if(!$cat_id){
            $cat = new PicvocCat();
        }else{
          $cat = PicvocCat::find($cat_id); 
        }
        $image_link = $req->img_link;
        if($image_link){
            $image_name = \App\library\OcoderHelper::getFileName($image_link);
            $image_name = "cat/".date('YmdHis').$image_name;
            $status_us = Storage::disk('picvoc_image')->put($image_name, file_get_contents($image_link));
            
            $img_data = getimagesize("../api/image/picvoc/".$image_name);
//            dd($img_data);
//            "{"x":0,"y":0,"height":360,"width":640,"rotate":0}"
            $data = new \stdClass();
            $data->x = 0;
            $data->y = 0;
            $data->rotate = 0;
            $data->width = $img_data[0];
            $data->height = $img_data[1];
            $crop = new CropPicVoc("../api/image/picvoc/".$image_name, json_encode($data), null, "../api/image/picvoc/cat");
            $crop->cropImg();
            $result = $crop->getResult();
            $save_url = str_replace("../api/image/", "", $result);
            unlink("../api/image/picvoc/".$image_name);
            if($status_us){
                $cat->img = $save_url;
                $cat->save();
            }
 
        }else{
            $cat->img = $req->img;
        }
        $cat->status = $req->status ? 1 : 0;
//        dd($req->status);
//        dd($cat->status);
        if($req->title){
            $cat->title = $req->title;
        }
        $cat->parent_id = $req->parent_id;
        $cat->save();
        Input::flash();
        return Redirect::to('/admin/picvoc/cat/'.$cat->id);
    }
    public function postUpdateCatOrder(Request $req){
        if(!$req->id){
            return json_encode(false);
        }
        $cat = PicvocCat::find($req->id);
        $order = $req->ordering;
        if($order <= 0){
            return json_encode(false);
        }
        if($cat->ordering == 0){
            PicvocCat::where("ordering",">=", $order)->update([
                    'ordering'=> DB::raw('ordering + 1') 
                ]);
        } else{
            if($order < $cat->ordering){
                PicvocCat::where("ordering","<", $cat->ordering)->where("ordering",">=", $order)->where("ordering",">",0)->update([
                    'ordering'=> DB::raw('ordering + 1') 
                ]);
            }else if($order > $cat->ordering){
                PicvocCat::where("ordering",">", $cat->ordering)->where("ordering","<=", $order)->where("ordering",">",0)->update([
                    'ordering'=> DB::raw('ordering - 1') 
                ]);
            }
        }
        $cat->ordering = $order;
        $cat->save();
        return json_encode(true);
        
    }
    public function updateCatOrders(){
        $cats = PicvocCat::where("parent_id",">",1)->orderBy("ordering","asc")->get();
        $order = 1;
        foreach($cats as $cat){
            if($cat->ordering == 0){
                $cat->ordering = $cat->id;
                $cat->save();
            }else{
                if($cat->ordering > $order){
                    $cat->ordering = $order;
                    $cat->save();
                }
                $order++;
            }
        }
        
        return Redirect::to('/admin/picvoc/cats');
    }
     public function updateVocOrders(){
        $id = Input::get('cat_id', '0');

        $cats = Voc::where("ordering",">",0)->orderBy("ordering","asc")->orderBy("liked","desc")->get();
        $order = 1;
        foreach($cats as $cat){
//            if($cat->ordering == 0){
//                $cat->ordering = $order;
//                $cat->save();
//            }else{
                if($cat->ordering != $order){
                    $cat->ordering = $order;
                    $cat->save();
                }
                $order++;
//            }
        }
        $cats = Voc::where("ordering",0)->orderBy("liked","desc")->get();
        foreach($cats as $cat){ 
            $cat->ordering = $order;
            $cat->save(); 
            $order++;
        }
        
        if($id){
            return Redirect::to('/admin/picvoc/vocabularies/'.$id);
        }else{
            return Redirect::to('/admin/picvoc/cats');
        }
    }
    
    public function postUpdateVocOrder(Request $req){
        if(!$req->id){
            return json_encode(false);
        }
        $voc = Voc::find($req->id);
        $order = $req->ordering;
        if($order <= 0){
            return json_encode(false);
        }
        if($voc->ordering == 0){
            Voc::where("ordering",">=", $order)->update([
                    'ordering'=> DB::raw('ordering + 1') 
                ]);
        } else{
            if($order < $voc->ordering){
                Voc::where("ordering","<", $voc->ordering)->where("ordering",">=", $order)->where("ordering",">",0)->update([
                    'ordering'=> DB::raw('ordering + 1') 
                ]);
            }else if($order > $voc->ordering){
                Voc::where("ordering",">", $voc->ordering)->where("ordering","<=", $order)->where("ordering",">",0)->update([
                    'ordering'=> DB::raw('ordering - 1') 
                ]);
            }
        }
        $voc->ordering = $order;
        $voc->save();
        return json_encode(true);
        
    }
    
    function formatBytes($bytes, $decimals = 2) { 
     $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) .' '. @$sz[$factor].'b';
} 
    public function vocs($cat_id){
        if(!$cat_id) return;
        $cat = PicvocCat::find($cat_id);
         if (@$_GET['sort_by']) {
            Session::put('pvv_sort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('pvv_sort_dimen', $dimen);
        }
        $sort_by = Session::get('pvv_sort_by', 'ordering');
        $sort_dimen  = Session::get('pvv_sort_dimen', 'asc');
        $vocs = $cat->vocs()->orderBy($sort_by, $sort_dimen)->paginate(20);
        foreach ($vocs as $voc){
             if($voc->image && file_exists("../api/image/picvoc/".$voc->image)){
                $voc->img_data = getimagesize("../api/image/picvoc/".$voc->image);
                $voc->img_size = $this->formatBytes(filesize("../api/image/picvoc/".$voc->image));
            
            }else{
                $voc->img_data = null;
            }
        }
         
        return view('admin/picvoc/vocs', compact('cat', 'vocs','sort_by','sort_dimen'));
    }
    
    public function voc($voc_id){
        if(!$voc_id) return;
        $voc = Voc::find($voc_id);
        $means = $voc->means;
        $cats = PicvocCat::where("parent_id",">",1)->orderBy("ordering","asc")->get();
        $cat_ids = [];
        foreach($voc->cats as $cat){
            $cat_ids[] = $cat->id;
        }
        return view('admin/picvoc/voc', compact('voc','means','cats','cat_ids'));
    }
	
	public function means(){
         if (@$_GET['sort_by']) {
            Session::put('pvm_sort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('pvm_sort_dimen', $dimen);
        }
        $sort_by = Session::get('pvm_sort_by', 'updated');
        $sort_dimen  = Session::get('pvm_sort_dimen', 'desc');
        $means = PicvocMean::orderBy($sort_by, $sort_dimen)->paginate(30);
        return view('admin/picvoc/means', compact('means','sort_by','sort_dimen'));

    }
	
    public function updatePron(){
        $vocs = Voc::where("update_pron", 0)->paginate(20);
//        dd($vocs);
		echo '<html>
                <head>
                    <title>Crawl Mean 20s</title>
                    <meta http-equiv="refresh" content="20" />
                </head>
                <body>';
        foreach ($vocs as $voc){
            $this->_updatePron($voc);  
        }
    }
	
	private function _updatePron($voc){
		echo "<div style='margin-bottom: 10px; border: 1px solid; padding: 5px;'>".$voc->en_us."<br>";
            
          
			$word = CommonWord::where("word",$voc->en_us)->first();	
 
//             URL::route('grammar.edit_cat', $word->id);  
            if(!$word){
                $voc->update_pron = 2;
				$voc->status = 0;
                $voc->save();
				echo "<br>No Word</div>";
                return;
            }         
					
			echo   " <a href='". \Illuminate\Support\Facades\URL::to('/')."/admin/dictionary/word/".$word->id."' target='_blank' >".$word->word."</a>"."<br>";
			echo   " <a href='". \Illuminate\Support\Facades\URL::to('/')."/admin/dictionary/word/reset/".$word->id."' target='_blank' >Reset ".$word->word."</a>"." ";	
			echo   " <b><a href='". \Illuminate\Support\Facades\URL::to('/')."/api/looked-up/crawl?word_id=".$word->id."' target='_blank' >Re-Crawl ".$word->word."</a></b>"."<br>";	
			echo   " <b><a href='". \Illuminate\Support\Facades\URL::to('/')."/admin/picvoc/update-pron-by-id/".$voc->id."' target='_blank' >Re-Crawl ".$voc->en_us." " . $voc->id."</a></b>"."<br>";	

 	 
            //en us audio
            $voc->en_us_pr = '/' .$word->en_us_pro . '/';
            $en_us_mp3 =  $word->en_us_audio;
            $mp3_us_file = \App\library\OcoderHelper::getFileName($en_us_mp3);
            if(!$mp3_us_file){
                $voc->update_pron = 2;
                $voc->status = 0;
                $voc->save();
                echo "<br>No US mp3</div>";
                return;
            }
           
             
            //get audio
            if (Storage::disk('picvoc_audios')->has($mp3_us_file)) {
               Storage::disk('picvoc_audios')->delete($mp3_us_file);
            }
            
             echo "<b>Audio:</b>" . $mp3_us_file . "<br>";
            $status_us = Storage::disk('picvoc_audios')->put($mp3_us_file, file_get_contents($en_us_mp3));
                
            if($status_us){
                $voc->en_us_audio = $mp3_us_file;
            }else{
                echo "<br>No US mp3</div>";
                return;
            }
            //en uk audio
            $voc->en_uk_pr = '/' .$word->en_uk_pro . '/';
            $en_uk_mp3 =  $word->en_uk_audio;
            $mp3_en_uk_file = \App\library\OcoderHelper::getFileName($en_uk_mp3);
             if(!$mp3_en_uk_file){
                $voc->update_pron = 2;
                $voc->status = 0;
                $voc->save();
                echo "<br>No UK mp3</div>";
                return;
            }
             //get audio
            if(Storage::disk('picvoc_audios')->has($mp3_en_uk_file)) {
               Storage::disk('picvoc_audios')->delete($mp3_en_uk_file);
            }
            echo "<b>Audio:</b>" . $mp3_en_uk_file . "<br>";
            $status_uk = Storage::disk('picvoc_audios')->put($mp3_en_uk_file, file_get_contents($en_uk_mp3));
                
            if($status_uk){
                $voc->en_uk_audio = $mp3_en_uk_file;
            }else{
                echo "<br>No UK mp3</div>";
                return;
            }
            $voc->update_pron = 1;
            $voc->save();  
             echo   " <b><br><a href='". \Illuminate\Support\Facades\URL::to('/')."/admin/picvoc/vocabularies/".$voc->cats[0]->id."' target='_blank' >Cat ".$voc->cats[0]->title." " . $voc->cats[0]->id."</a></b>"."<br>";	

            echo "</div>"; 
            return;
		
	}
	  public function updatePronById($voc_id){
        if(!$voc_id) return;
        $voc = Voc::find($voc_id);
        $this->_updatePron($voc);  
    }
	 /**
     * ajax
     */
     public function ajaxPublish() {
        $id = Input::get('voc_id', '0');
        $status = Input::get('status', false);
        $voc = Voc::find($id);
        $voc->status = $status ? 1 : 0;
        $voc->save();
        return response()->json(['status' => $status, "id"=>$id]);
    }
    
    public function postCatImage(Request $req) {
        $cat_id = $req->id;
        if(!$cat_id) return;
        $cat = PicvocCat::find($cat_id); 
        
        $src = isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null;
        $data = isset($_POST['avatar_data']) ? $_POST['avatar_data'] : null;
        $file = isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null;
//        $src = "/../api/image/".$src; 
//        dd($data);
        $crop = new CropPicVoc($src, $data, $file, "../api/image/picvoc/cat");
        
        $crop->cropImg();
        $result = $crop->getResult();
//        dd($result);
        $isFileExist = file_exists($result);
//        dd($isFileExist);
//        $result = str_replace("/../api/image/", "", $result);
        $save_url = str_replace("../api/image/", "", $result);
//        dd($crop);
        $response = array(
            'state' => 200,
            'message' => $crop->getMsg(),
            'result' => \Illuminate\Support\Facades\URL::to('/')."/".$result
        );

        $cat->img = $save_url;
        $cat->save();
 
        echo json_encode($response);
    }
    
    public function postVocImageLink(Request $req) {
        $voc_id = $req->id;
        if(!$voc_id) return;
        $image_link = $req->link;
        $response = new \stdClass();

         if($image_link){
            $image_name = \App\library\OcoderHelper::getFileName($image_link);
            $image_name =   date('YmdHis').$image_name;
            $status = Storage::disk('picvoc_image')->put($image_name, file_get_contents($image_link));
             if(!$status){
                 $response->status = false;
                 echo json_encode($response);
                 return;
             }
            $img_data = getimagesize("../api/image/picvoc/".$image_name);
            
//            dd($img_data);
//            "{"x":0,"y":0,"height":360,"width":640,"rotate":0}"
            $data = new \stdClass();
            $data->x = 0;
            $data->y = 0;
            $data->rotate = 0;
            $data->width = $img_data[0];
            $data->height = $img_data[1];
            $crop = new CropPicVoc("../api/image/picvoc/".$image_name, json_encode($data), null, "../api/image/picvoc");
            $crop->setDesWidth(1000);
            $crop->cropImg();
            $result = $crop->getResult();
            $save_url = str_replace("../api/image/picvoc/", "", $result);
             
            unlink("../api/image/picvoc/".$image_name);
              $voc = Voc::find($voc_id);
            $voc->image = $save_url;
            $voc->save();
            $response->status = true;
            $response->src = \Illuminate\Support\Facades\URL::to('/')."/".$result;
            $response->size = $this->formatBytes(filesize($result));
      
        }else{
             $response->status = false;
        }
       
 
        echo json_encode($response);
    }
    
    public function postVocImage(Request $req) {
        $voc_id = $req->id;
        if(!$voc_id) return;
        $voc = Voc::find($voc_id); 
         $src = isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null;
        $data = isset($_POST['avatar_data']) ? $_POST['avatar_data'] : null;
        $file = isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null;
//        $src = "/../api/image/".$src; 
        
        $crop = new CropPicVoc($src, $data, $file, "../api/image/picvoc");
        $crop->setDesWidth(1000);
        $crop->cropImg();
        $result = $crop->getResult();
        
//        $isFileExist = file_exists($result);
//        dd($isFileExist);
//        $result = str_replace("/../api/image/", "", $result);
        $save_url = str_replace("../api/image/picvoc/", "", $result);
       
//        dd($crop);
        $response = array(
            'state' => 200,
            'message' => $crop->getMsg(),
            'result' => \Illuminate\Support\Facades\URL::to('/')."/".$result
        );

        $voc->image = $save_url;
        $voc->save();
 
        echo json_encode($response);
    }
    
    
    public function searchVoc(){
        $search = trim(Input::get("search",""));
        $vocs = Voc::where("en_us", "like", "%$search%")->paginate(5);
        $vocs->appends(['search' => $search]);
        return view('admin/picvoc/search', compact( 'vocs'));
        
    }
    
     public function ajaxGetVocs() {
        $cat_term = Input::get('term', '');

        $cats = Voc::where('en_us', 'like', "%$cat_term%")->take(20)->get();

        $return = [];
        foreach ($cats as $cat) {
            $return[] = ['key' => $cat->id, 'value' => $cat->en_us];
        }
        return response()->json($return);
    }
}
