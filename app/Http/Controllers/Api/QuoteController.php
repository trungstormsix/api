<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

use App\library\DomParser;
//model
use DB;
use App\Models\Quote\Quote;
use App\Models\Quote\Author;
use App\Models\Quote\Categories;
use App\Models\Quote\Tags;

class QuoteController extends Controller {

    //put your code here

    public function cats() { 
//        $cats = Categories::paginate(10);
        $cats = Categories::get();

//        $cats = Categories::where("done",1)->get();
        return response()->json($cats);
    }
      public function authors() { 
        $authors = Author::paginate(10);
//        $cats = Categories::where("done",1)->get();
        return response()->json($authors);
    }
    public function quoteByCat($cat_id){
        $updated = Input::get("max_date","0000-00-00 00:00:01");
         
        $cat = Categories::find($cat_id);
        $quotes = $cat->quotes()->where("updated_at",">=",$updated)->orderBy("updated_at", "asc")->limit(60)->get();
        foreach ($quotes as $quote){
            $quote->author = $quote->author;
            $quote->cats = $quote->getCatIdsAttribute();
        }
        return response()->json($quotes);
    }

    public function quoteByAuthor($author_id){
        $updated = Input::get("updated","0000-00-00 00:00:01");
        $author = Author::find($author_id);
        $quotes = $author->quotes()->where("updated_at",">=",$updated)->paginate(50);
        foreach ($quotes as $quote){
            $quote->cats = $quote->getCatIdsAttribute;            
        }
        return response()->json($quotes);
    }
    public function author($author_id){
        $author_id = $author_id  ? $author_id : 1;
        $author = Author::find($author_id);
//        $quotes = $author->quotes()->where("updated_at",">=",$updated)->paginate(50);
//        foreach ($quotes as $quote){
//            $quote->cats = $quote->getCatIdsAttribute;            
//        }
        return response()->json($author);
    }
    
     public function setVote(){
        $id = Input::get("id");
        $like = Input::get("like");
        if($like > 0){
            //ListeningDialog::where("id",$id)->increment('liked');
            if(Quote::find($id)->favorite % 5 ==0){
                Quote::where("id",$id)->increment('favorite');
            }else{
                DB::table('quotes') ->where('id', $id)->increment('favorite');
            }
        }else{
            //ListeningDialog::where("id",$id)->decrement('liked');
            DB::table('quotes') ->where('id', $id)->decrement('favorite');
        }
       return Quote::find($id)->favorite;
    }
}
