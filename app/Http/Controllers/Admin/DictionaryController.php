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

    public function lookedUp($lang = "vi") {
//        $lang = Input::get('lang','vi');
        $langs = DB::table('common_word_mean')
                ->select('lang', DB::raw('SUM(count) as total'))
                ->groupBy('lang')
                ->orderBy('total', "DESC")
                ->get();
        $words = Dictionary::where("lang", $lang)->orderBy("count", "DESC")->paginate(10);
        $word = $words[0];
         
        $json_text = file_get_contents("https://glosbe.com/gapi_v0_1/translate?from=eng&dest=$lang&format=json&phrase=" . $word->word->word . "&page=1&pretty=false&tm=true");
        $json = json_decode($json_text);
        $mean = $json->tuc;
        $example = $json->examples;
        $word->mean = json_encode($mean);
        $word->example = json_encode($example);
        $word->save();
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
        if (!$word->mean || !$word->example) {
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

}
