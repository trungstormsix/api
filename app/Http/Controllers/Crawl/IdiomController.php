<?php

namespace App\Http\Controllers\Crawl;


use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\User;
use App\Models\Idiom;
use App\Models\IdiomCat;
use App\Models\IdiomExample;
 
use File;
use Illuminate\Support\Facades\Session;

class IdiomController  extends Controller {
 
    var $url = 'http://idioms.thefreedictionary.com/';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }
    
    

    public function getExample(){
         
        $dom = new \App\library\DomParser();
        $idioms = Idiom::where('example',Null)->where('is_got',null)->take(5)->get();
        if(!$idioms){
            echo 'finished';
            exit;
        }
        $data = [];
        foreach ($idioms as $idiom){
            $idiom->word = strtolower($idiom->word);
            $idiom_world = str_replace(' ', '+', $idiom->word);
            $idiom->is_got = 0; 
             $examples = $this->_crawlExample($this->url.$idiom_world);
            if($examples){
                $idiom->example = json_encode($examples);
                $idiom->is_got = 1;
                $idiom->updated = date('Y-m-d H:i:s');
            }        
            $idiom->save();
             
            $data[] = $idiom->word .' got: '.$idiom->is_got;
           
        }
        $total = Idiom::where('is_got',1)->count();
        $data[] = '<h2>Total Got: '.$total.'</h2>';
        return view('layouts/autoRefresh',['data' => $data]);

    }
    
    private function _crawlExample($url){
        $dom = new \App\library\DomParser();
         @$exp_html = $dom->file_get_html($url);
        if($exp_html){
            $exps = $exp_html->find('#Definition .ds-single .illustration');
            $examples = [];
            foreach ($exps as $exp){
                $example_text = $exp->plaintext;
                $example = IdiomExample::where('example','=', $example_text)->first();
                if(!$example){
                    $example = new  IdiomExample();
                    $example->example = $example_text;
                    $example->save();
                }
                
                if(!in_array($example->id, $examples)){
                    $examples[] = $example->id;
                }
            }
            return $examples;
        }
        return null;
    }

    
     
}
