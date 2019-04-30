<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image\Images;
use App\Models\Image\ImgCat;
use Illuminate\Support\Facades\Session;
use App\library\CropAvatar;
use Illuminate\Support\Facades\Storage;
use App\library\OcoderHelper;

class ImageController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $articles = Images::paginate(45);
        return view('admin.articles.home', ['articles' => $articles]);
    }

    public function items($cat_id) {
        $cat = ImgCat::find($cat_id);
        \Session::set('img_cat_id', $cat_id);

        $title = $cat->name;
        $trash = \Illuminate\Support\Facades\Input::get('trash', false);
        if (!$trash) {
            $query = Images::where('cat_id', $cat_id);
        } else {
            $query = $articles = Images::where('cat_id', $cat_id)->where("published", 2);
        }
        $articles = $query->paginate(45);
        return view('admin.img_item.home', compact('articles', 'title', 'trash', 'cat'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $categories = ImgCat::all();
        $categories_level = ImgCat::where(['parent_id' => 0])->get();
        return view('admin.img_item.edit', ['categories' => $categories, 'categories_level' => $categories_level]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $articles = Images::findOrFail($id);
        $categories = ImgCat::all();
        $categories_level = ImgCat::where(['parent_id' => 0])->get();
        return view('admin.img_item.edit', compact('articles', 'categories', 'categories_level'));
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
        if (!$request->thumb) {
            $request->thumb = $request->main_img;
        }
        $main_img = OcoderHelper::dowloadImage($request->main_img);
         
        $thumb = "";
        if ($request->thumb) {
            $thumb = OcoderHelper::dowloadImage($request->thumb);
        }
        $result = $articles->update($request->all());
        if($request->cat_id)
        \Session::set('img_cat_id', $request->cat_id);
        if ($result) {
            if ($main_img != $articles->main_img) {
                if($articles->main_img) {
                    $org_main = substr($articles->main_img, 0,1) == "/" ? substr($articles->main_img, 1) : "";
                    if (file_exists($org_main)) {
                        unlink($articles->main_img);
                    }
                    $articles->main_img = $main_img;
                }
            }
            if ($thumb) {
                $articles->thumb = $thumb;
            }
            $articles->save();
            Session::flash('success', 'Article saved successfully!');
        } else {
            Session::flash('error', 'Article failed to save successfully!');
        }
        if ($articles && $articles->id) {
            if(@$request->save_and_new){
                return redirect()->route('image.createImg')->with(['success' => 'Image created successfully!']);;

            }else{
                return redirect()->route('image.editImg', ['id' => $articles->id])->with(['success' => 'Image created successfully!']);;
            }
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
        $articles = Images::find($id);
        $cat_id = $articles->cat_id;
        $articles->delete();
        if($cat_id){
            return redirect()->route('image.listItem', ['cat_id' => $cat_id]);
        }else{
            return redirect()->route('image.cats');
        }
    }

    public function deleteImgs(Request $request) {

        $ids = $request->get("ids");
        $delete = $request->get("delete");
        $trash = null;
        foreach ($ids as $article_id) {
            $image = Images::find($article_id);
            
            if (!$image)
                continue;
            if($image->cat_id){
                $cat_id = $image->cat_id;
            }
            if ($delete == 3) {
                $trash = "trash=1";
                $image->delete();
            } else {
                //re-publish
                $image->published = $delete;
                $image->save();
            }
        }



        return redirect()->route('image.listItem', ['trash' => $trash, 'cat_id' => $cat_id]);
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
