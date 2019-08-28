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
use File;
use Illuminate\Support\Facades\Session;
use App\Models\CommonWord;
use App\Models\Dictionary;
use Illuminate\Support\Facades\DB;

class DictionaryController extends AdminBaseController {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $langs = DB::table('common_word_mean')
                ->select('lang', DB::raw('SUM(count) as total'))
                ->groupBy('lang')
                ->orderBy('total', "DESC")
                ->get();
        $words = CommonWord::orderBy('count', 'desc')->paginate(30);
        return view('admin/dictionary/lookedup', compact("words", 'langs'));
    }
    public function resetNotGetMean(){
        

        $update = \DB::table('common_word_mean') ->where('get',0)->where("mean","!=","") ->limit(100) ->update( [ 'mean' => "", 'example' => ""]); 
        dd($update);

    }

    public function crawl(){
            $json_text = file_get_contents("http://api.dogiadungchinhhang.com/api/dic/crawl-mean");
            $means = json_decode($json_text);
             echo '<html>
    <head>
        <title>Crawl Mean 15s</title>
        <meta http-equiv="refresh" content="15" />
    </head>
    <body>';
            foreach($means->data as $mean){       
                $lWord = Dictionary::find($mean->id);
                echo "<b>".$mean->word->word." ".$mean->lang."</b><br>";
                if(!$lWord){
                    $lWord = new Dictionary();
                    $lWord->id = $mean->id;
                    $lWord->word_id = $mean->word_id;
                    $lWord->lang = $mean->lang;
                    $lWord->mean = $mean->mean;
                    $lWord->example = $mean->example;
                    $lWord->get = $mean->get;
                    $lWord->created_at = $mean->created_at;
                    $lWord->updated_at = $mean->updated_at;
                    $lWord->count = $mean->count;
                    $lWord->save();
                }
            }
           
    }
    public function lookedUp($lang = "vi") {
//        $lang = Input::get('lang','vi');
        $langs = DB::table('common_word_mean')
                ->select('lang', DB::raw('SUM(count) as total'))
                ->groupBy('lang')
                ->orderBy('total', "DESC")
                ->get();
        $words = Dictionary::where("lang", $lang)->orderBy("count", "DESC")->paginate(10);
        $word = $words[0];
        //crawl word
        if(false){
        $json_text = file_get_contents("https://glosbe.com/gapi_v0_1/translate?from=eng&dest=$lang&format=json&phrase=" . $word->word->word . "&page=1&pretty=false&tm=true");
        $json = json_decode($json_text);
        $mean = $json->tuc;
        $example = $json->examples;
        $word->mean = json_encode($mean);
        $word->example = json_encode($example);
        $word->save();
        }
		foreach($words as &$word){
			if($word->mean)
			$word->word->word  .= "(".strlen($word->mean).")" ." (".strlen($word->example).")";
		}
		
        return view('admin/dictionary/listwords', compact("words", "lang", "langs"));
    }
    public function refresh($id = 1) {
        $langs = DB::table('common_word_mean')
                ->select('lang', DB::raw('SUM(count) as total'))
                ->groupBy('lang')
                ->orderBy('total', "DESC")
                ->get();
        $word = Dictionary::find($id);
        $link = "https://glosbe.com/gapi_v0_1/translate?from=eng&dest=$word->lang&format=json&phrase=" . $word->word->word . "&page=1&pretty=false&tm=true";
        echo $link;
            $json_text = file_get_contents($link);
            $json = json_decode($json_text);
            $mean = $json->tuc;
            $example = $json->examples;
            $word->mean = json_encode($mean);
            $word->example = json_encode($example);
            $word->save();
 
        return view('admin/dictionary/edit', compact("word", "langs",'link'));
    }
    public function edit($id = 1) {
        $langs = DB::table('common_word_mean')
                ->select('lang', DB::raw('SUM(count) as total'))
                ->groupBy('lang')
                ->orderBy('total', "DESC")
                ->get();
        $word = Dictionary::find($id);
		$link = "https://glosbe.com/gapi_v0_1/translate?from=eng&dest=$word->lang&format=json&phrase=" . $word->word->word . "&page=1&pretty=false&tm=true";

        if (false && (!$word->mean || !$word->example)) {

            $json_text = file_get_contents($link);
            $json = json_decode($json_text);
            $mean = $json->tuc;
            $example = $json->examples;
            $word->mean = json_encode($mean);
            $word->example = json_encode($example);
            $word->save();
        }
//        dd(json_decode($word->mean));
        return view('admin/dictionary/edit', compact("word", "langs",'link'));
    }

	 public function deleteMean($id = 1) {
        $langs = DB::table('common_word_mean')
                ->select('lang', DB::raw('SUM(count) as total'))
                ->groupBy('lang')
                ->orderBy('total', "DESC")
                ->get();
        $word = Dictionary::find($id);
		 
		$word->mean = "";
		$word->example = "";
		$word->get = 0;
		$word->save();
		echo "<b>".$word->word."</b> deleted<br>";

    }
	public function deleteMeanSearch() {
         
        $words = Dictionary::whereRaw('LENGTH(mean) > 7000')->orderByRaw('CHAR_LENGTH(mean) desc')->paginate(45);
		if(!$words || sizeof($words) == 0){
			$words = Dictionary::whereRaw('LENGTH(example) > 7000')->orderByRaw('CHAR_LENGTH(mean) desc')->paginate(45);

		}
		if($words){
			echo '<html>
    <head>
        <title>HTML in 10 Simple Steps or Less</title>
        <meta http-equiv="refresh" content="15" />
    </head>
    <body>';
		}
		foreach($words as $word){	
			echo "<b>".$word->word."</b> deleted<br>";
			$word->mean = "";
			$word->example = "";
			$word->get = 0;
			$word->save();
        }
		
		 

    }
	
	public function getDeleteMeanSearch() {
        $lang = "delete";
        $words = Dictionary::whereRaw('LENGTH(mean) > 8000')->orderByRaw('CHAR_LENGTH(mean) desc')->paginate(40);
		if(!$words || sizeof($words) == 0){
			$words = Dictionary::whereRaw('LENGTH(example) > 8000')->orderByRaw('CHAR_LENGTH(mean) desc')->paginate(40);

		}
		foreach($words as &$word){			 
			$word->word->word .= "(".strlen($word->mean).")" ." (".strlen($word->example).")";
			 
        }
		
		$langs = DB::table('common_word_mean')
                ->select('lang', DB::raw('SUM(count) as total'))
                ->groupBy('lang')
                ->orderBy('total', "DESC")
                ->get();
		return view('admin/dictionary/listwords', compact("words", "lang", "langs"));

    }
    public function save(Request $request) {
        $id = $request->get("id");
        $word = Dictionary::find($id);
        //for meanings
        $meanings = $request->meaning;
        $meaning_subs = $request->meaning_sub;
        $langs = $request->lang;
        $types = $request->type;
        $i = 0;
        $mean_arr = [];
         
        foreach($meanings as $mean){
            
            $m = new \stdClass();
            if($types[$i] == "phrase"){
                $m->phrase = new \stdClass();
                $m->phrase->text = $mean;
                $m->phrase->language = $langs[$i];   
                if(@$meaning_subs[$i]){
                for($j = 0; $j < sizeof($meaning_subs[$i]['mean']); $j++){
                    if(!$meaning_subs[$i]['mean'][$j]){
                        continue;
                    }
                    $sub_mean = new \stdClass();
                    $sub_mean->text = $meaning_subs[$i]['mean'][$j];
                    $sub_mean->language = $meaning_subs[$i]['lang'][$j];
                    $m->meanings[] = $sub_mean;
                }
                }
            }else{
                $m->meanings = [];
                $meaning = new \stdClass();
                $meaning->text = $mean;
                $meaning->language = $langs[$i];
                $m->meanings[0] = $meaning;
            }
            if($mean){
                $mean_arr[] = $m;
            }
            $i++;
        };
//        dd($mean_arr);
       
        $word->mean = json_encode($mean_arr);
        //for examples
        $exs = $request->example;
        $exms = $request->exampleMean;
        $example_arr = [];
        for($i = 0; $i < sizeof($exs); $i++){
            if(!$exs[$i]){
                continue;
            }
            $example = new \stdClass();
            $example->first = $exs[$i];
            $example->second = $exms[$i];
            $example_arr[] = $example;
        }
        $word->example = json_encode($example_arr);
        $word->save();
        return redirect('admin/dictionary/edit/' . $word->id);
    }
    
    public function search(){
        $search = Input::get("search","");
        $words = null;
        if($search){
          $words =  CommonWord::where("word","like",$search)->whereOr("en_uk_pro","like",$search)->paginate(40);
          $words->appends(['search' => $search]);
        }
                
        return view('admin/dictionary/search', compact("words", "search"));
   
    }
	public function deleteSearch() {
		 $search = Input::get("search","");
        if(!$search){
            echo "Search fail";
        }
		if($search){
          $words =  CommonWord::where("word","like",$search)->whereOr("en_uk_pro","like",$search)->paginate(40);
          $words->appends(['search' => $search]);
        }else{
			$words =  CommonWord::whereRaw('LENGTH(word) > 8')->where("can_delete",1)->orderByRaw('CHAR_LENGTH(word) desc')->paginate(40);
			if($words && sizeof($words) > 1){
				echo '<html>
    <head>
        <title>HTML in 10 Simple Steps or Less</title>
        <meta http-equiv="refresh" content="15" />
    </head>
    <body>';
			}

		}
		foreach($words as $word){
			//$word = CommonWord::find($id);
			if($word->en_us_pro){
				echo "exist <b>".htmlentities($word->word)."</b> Do nothing<br><br>";
				$word->can_delete = 0;
					$word->save();
				continue;
			}
			//echo " not exist ".htmlentities($word->word)."<br>";

			$word_means = $word->means;
			 
			if($word_means){
				
				$delete = true;
				foreach($word_means as $mean){										
					if($mean->mean && $mean->mean != null){	
						echo "value: ".htmlentities($mean->mean)."<br>";						 
						$delete = false;
						continue;
					}
					$uws = $mean->userWord;
					if($uws){
						foreach($uws as $u){
							$u->delete();
						}
					}
					$mean->delete();
				}
				if($delete){
					$word->delete();
					echo "Word: <b>".htmlentities($word->word)."</b><br>Deleted completely<br><br>";
				}else{
					echo "Word: <b>".htmlentities($word->word)."</b><br>Has mean, do not delete!<br><br>";
					$word->can_delete = 0;
					$word->save();
				}
			}else{
				$word->delete();
				echo "Word: <b>".htmlentities($word->word)."</b><br>Deleted completely<br><br>";
			}
		}
		exit;
    }
    public function delete($id = 1) {
        if(!$id){
            echo "Id fail";
        }
        $word = CommonWord::find($id);
        $word_means = $word->means;
        if($word_means){
            foreach($word_means as $mean){
                $uws = $mean->userWord;
                if($uws){
                    foreach($uws as $u){
                        $u->delete();
                    }
                }
                $mean->delete();
            }
        }
        $word->delete();
        echo "Word: <b>".$word->word."</b><br>Deleted completely";
    }
}
