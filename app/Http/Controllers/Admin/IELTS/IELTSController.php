<?php

namespace App\Http\Controllers\Admin\IELTS;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\library\OcoderHelper;
use Illuminate\Support\Facades\Storage;
use App\library\DomParser;

use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\User;
use App\Models\ListeningCat;
use App\Models\ListeningDialog;
use File;
use Illuminate\Support\Facades\Session;
use App\Models\IELTS\IELTSCat;
use App\Models\IELTS\IELTSArticle;
use App\Models\IELTS\IELTSVocabulary;

class IELTSController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * list all cats
     * @return type
     */
    public function index() {
        $cats = IELTSCat::where("type", "article")->get(); //all();
        return view('admin/ielts/cats', ['cats' => $cats]);
    }

    public function vocabulary() {
        $cats = IELTSCat::where("type", "General")->orWhere("type", "Academic")->get(); //all();
        return view('admin/ielts/cats', ['cats' => $cats]);
    }

    /**
     * list all cats
     * @return type
     */
    public function editCat($id = 0) {
        $cat = IELTSCat::find($id); //all();
        return view('admin/ielts/cat', ['cat' => $cat]);
    }

    /**
     * save cat
     */
    public function postCat(Request $req) {
        if ($req->id) {
            $cat = IELTSCat::find($req->id);
        } else {
            $cat = IELTSCat::where('title', $req->title)->first();
            if (!$cat) {
                $cat = new IELTSCat();
            }
        }
        $cat->type = $req->type;

        if ($req->title) {
            Session::flash('success', 'IELTS Category saved successfully!');
            $cat->title = $req->title;
            $cat->save();
            return Redirect::to('/admin/ielts/edit-cat/' . $cat->id);
        }
        Session::flash('error', 'IELTS Category fail to save!');


        Input::flash();
        return Redirect::to('/admin/ielts/add-cat');
    }

	public function search() {
		$search = Input::get('search', "");
         if (@$_GET['sort_by']) {
            Session::put('sort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('sort_dimen', $dimen);
        }
		$sort_by = Session::get('sort_by', 'status');
        $dimen = Session::get('sort_dimen', 'desc');
        $articles = IELTSArticle::where("title","like","%".$search."%")->orderBy($sort_by, $dimen)->paginate(20);
		return view('admin/ielts/articles', ['articles' => $articles, "cat" => null, 'sort_by' => $sort_by, 'sort_dimen' => $dimen,'search'=>$search]);

    }
	
    public function listAll($cat_id = 0) {
        $cat = IELTSCat::find($cat_id);
		Session::put('il_cat_id', $cat_id);
		  
        if ($cat->type == "article") {
            return $this->_getArticles($cat);
        } else {
            //$this->_getVocs($cat_id);
        }
    }

    private function _getArticles($cat) {
        if (@$_GET['sort_by']) {
            Session::put('sort_by', $_GET['sort_by']);
            $dimen = @$_GET['sort_dimen'] ? $_GET['sort_dimen'] : 'asc';
            Session::put('sort_dimen', $dimen);
        }
        $sort_by = Session::get('sort_by', 'status');
        $dimen = Session::get('sort_dimen', 'desc');
        $articles = $cat->articles()->orderBy($sort_by, $dimen)->paginate(20);

//        dd($articles);
        return view('admin/ielts/articles', ['articles' => $articles, "cat" => $cat, 'sort_by' => $sort_by, 'sort_dimen' => $dimen]);
    }

    public function editArticle($article_id) {
        $cats = IELTSCat::where("type", "article")->get();

        $article = IELTSArticle::find($article_id); //all();
        return view('admin/ielts/article', ['article' => $article, 'cats' => $cats]);
    }

    /**
     * save cat
     */
    public function postArticle(Request $req) {

        $this->validate($req, [
            'title' => 'required|max:255',
        ]);
        if ($req->id) {
            $article = IELTSArticle::find($req->id);
        } else {
            $this->validate($req, [
                'title' => 'required|unique:il_articles|max:255',
            ]);
            $article = new IELTSArticle();
			$req->audio = trim(@$req->audio);
			
            $article->save($req->all());
        }
        $req->status = $req->status ? 1 : 0;
        $article->status = $req->status ? $req->status : 0;
		$audio = OcoderHelper::getFileName($req->audio);
		$audio = $audio ? "/ielts/".$req->category."/".$audio : "";
		
		//get audio
		if (!Storage::disk('audios')->has($audio) && $req->audio) {
			Storage::disk('audios')->put($audio, file_get_contents($req->audio));
			$req->audio = $audio ?  "http://apiv1.ocodereducation.com/audios".$audio : "";	

		}
		
		 	
        $result = $article->update($req->all());
		
        if ($result) {
			 
			if ($audio && Storage::disk('audios')->has($audio)) {
				$article->audio = $req->audio;
				$article->save();
			}else{
				$article->audio = "";
				$article->save();
			}
            Session::flash('success', 'IELTS Article saved successfully!');
            return Redirect::to('/admin/ielts/article/' . $article->id);
        }
        Session::flash('error', 'IELTS Article fail to save!');
        Input::flash();
        if ($article && $article->id) {
            return Redirect::to('/admin/ielts/article/' . $article->id);
        }
        return Redirect::to('/admin/ielts/article/add');
    }

    
    /**
     * vocabulary
     */
    public function updateWord($voc_id) {
        $voc = IELTSVocabulary::find($voc_id);
        $word = str_replace(" ", "+", $voc->en);
        $link = "http://www.oxfordlearnersdictionaries.com/definition/english/";
        $dom = new DomParser();
        $html = @$dom->file_get_html($link . $word . '_1');
        if (!$html) {
            $html = @$dom->file_get_html($link . $word);
        }
        if (!$html)
            return;
        $content = $html->find(".h-g", 0);
        if (!$content)
            return;
        $type_html = $content->find(".webtop-g .pos", 0);
        if (!$type_html)
            return;
        $type = $type_html->plaintext;
        $pron_html = $content->find(".pron-gs .phon", 0);
        if (!$pron_html)
            return;
        $pron = trim(preg_replace("/BrE|\//", "", $pron_html->plaintext));
        $pron = '/' . $pron . '/';
        if (!$content->find(".pron-gs .sound", 0))
            return;
        $mp3 = $content->find(".pron-gs .sound", 0)->getAttribute("data-src-mp3");

        $mp3_file = \App\library\OcoderHelper::getFileName($mp3);
        $audio = "/ielts/".$mp3_file;
        $status = true;
        //get audio
        if (!Storage::disk('listening_audios')->has($audio)) {
            echo "<b>Audio:</b>" . $audio . "<br>";

            $status = Storage::disk('listening_audios')->put($audio, file_get_contents($mp3));
        }
        $lis = @$content->find(".sn-g");
        $mean ="<ol>";
        foreach ($lis as $li){
            $mean  .="<li>";
            $mean .= "<p>".@$li->find('.gram-g', 0)->plaintext . @$li->find('.def', 0)->plaintext."</p>";
            $exs = $li->find(".x-g");
            $i = 0;
            foreach ($exs as $ex){
                $ex_text = "";
                if($ex->find(".cf")){
                    $ex_text = "<b>".$ex->find(".cf",0)->plaintext."</b>: ";
                    $ex_text .= $ex->find(".x",0) ->plaintext;
                }else{
                    $ex_text = $ex->plaintext;
                }
                $mean .= "<p>" .$ex_text . "</p>";
                if($i++ > 5) break;
            }
             $mean  .="</li>";
        }
        $mean .= "</ol>";
        echo "<b>Mean:</b>" . $mean . "<br>";
         

        /** save voc * */
         $voc->type = $type;
        $voc->pronuciation = $pron;
        
            $voc->mean = $mean;
         
        $voc->audio = $mp3_file;
       
        $voc->save();
  dd($voc);
        return true;
    }
}
