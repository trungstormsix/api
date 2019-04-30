<?php

namespace App\Http\Controllers\Crawl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image\Images;
use App\Models\Image\ImgCat;
use Illuminate\Support\Facades\Session;
use App\library\CropAvatar;
use Illuminate\Support\Facades\Storage;
use App\library\OcoderHelper;
use App\library\DomParser;
use Illuminate\Support\Facades\Input;


class ImageController extends Controller {
 
    public function getHairBuzzfeed(){
        $id = Input::get("id");
        if(!$id){
            
        }
        
        $link = "https://www.buzzfeed.com/augustafalletta/26-incredible-hairstyles-you-can-learn-in-10-steps-or-less";
      
  
        $dom = new DomParser();
        $html = $dom->str_get_html(file_get_contents($link));
        echo $html->find("body",0);
        exit;
        $article = $html->find("article.buzz",0);
        echo $article->ourtertext;
        exit;
    }
public function getHairTheLatest(){
        $id = Input::get("id");
        if(!$id){
            
        }
        
        echo  str_replace(url('/'), "", $link);
          echo url("/");
        exit;
        
      
  
        $dom = new DomParser();
        $html = $dom->file_get_html($link);
         
        $titles = $html->find(".entry-content h2");
        $imgs = $html->find(".entry-content .size-full ");
        $p = $html->find(".entry-content p");
        $i = 0;
        foreach($titles as $title){
            if($i != 0){         
           
            echo $title->innertext;
            echo $imgs[$i]->src;
            echo $p[$i*2]->src;
             }
            
        }
        $i++;
     
        exit;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        $id = $request->get("id");
        $result = false;
        $this->validate($request, [
            'title' => 'required|max:255',
        ]);
        if ($id == 0) {
            $articles = new Images();
            $articles->title = $request->title;
            $articles->save();
        } else {
            $articles = Images::findOrFail($id);
            if ($articles) {
                $articles->published = $request->published ? $request->published : 0;
            }
        }
        if(!$request->thumb){
            $request->thumb = $request->main_img;
        }
        $thumb = "";
        if($request->thumb){
            $thumb_host = parse_url($request->thumb)['host'];
            $current_host = parse_url(url('/'))['host'];

            if($thumb_host && $thumb_host != $current_host){
                $thumb = 'imgs/' . OcoderHelper::getFileName($request->thumb);                         
                    if (!Storage::disk('images')->has($thumb)) {
                        @Storage::disk('images')->put($thumb, file_get_contents($request->thumb));
                        $thumb = "/images/".$thumb;

                    }else{
                        $thumb = "/images/".$thumb;
                    }
            }
        }
        $result = $articles->update($request->all());

        if ($result) {
            if($thumb){
                $articles->thumb = $thumb;
                $articles->save();
            }
            Session::flash('success', 'Article saved successfully!');
        } else {
            Session::flash('error', 'Article failed to save successfully!');
        }
        if ($articles && $articles->id) {
            return redirect()->route('image.editImg', ['id' => $articles->id]); 
        }
        return redirect()->route('image.createImg');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function delete($id) {
        $cat_id = \Session::get('a_cat_id');

        $articles = Articles::find($id);
        $articles->delete();
        return redirect()->route('articels.list_cat', ['cat_id' => $cat_id]);
    }

    public function postDeleteArts(Request $request) {

        $cat_id = \Session::get('a_cat_id');

        $ids = $request->get("ids");
        $delete = $request->get("delete");
        $trash = null;
        foreach ($ids as $article_id) {
            $article = Articles::find($article_id);
            if (!$article)
                continue;
            if ($delete == 3) {
                $trash = "trash=1";
                $article->delete();
            } else {
                //re-publish
                $article->published = $delete;
                $article->save();
            }
        }



        return redirect()->route('articels.list_cat', ['trash' => $trash, 'cat_id' => $cat_id]);
    }

    public function postThumbImage() {
        $src = isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null;
        $data = isset($_POST['avatar_data']) ? $_POST['avatar_data'] : null;
        $file = isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null;
        $crop = new CropAvatar($src, $data, $file);
        $result = $crop->getResult();
        $response = array(
            'state' => 200,
            'message' => $crop->getMsg(),
            'result' => $result
        );
        $img = Images::find($_POST['id']);
        $org_thumb = $img->thumb ? substr($img->thumb, 1) : "";
        if ($img->thumb != $img->main_img && file_exists($org_thumb)) {
 
            unlink($org_thumb);
        }
        $img->thumb = $result;
        $img->save();
//        $user = Auth::user();
//        if ($result) {
//            $user->img = $result;
//            $user->save();
//        }
        echo json_encode($response);
    }
    
}
