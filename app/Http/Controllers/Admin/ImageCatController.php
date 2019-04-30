<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image\ImgCat;
use Illuminate\Support\Facades\Session;

class ImageCatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($cat_id = 0)
    {
        if($cat_id){
          $categories =   ImgCat::where("parent_id", $cat_id)->paginate(20);
        }else{
            $categories = ImgCat::paginate(20);
        }
        return view('admin.img_cat.cats', ['categories' => $categories]);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ImgCat::all();
        $categories_level = ImgCat::where(['parent_id' => 0])->get();
        return view('admin.img_cat.edit', ['categories' => $categories, 'categories_level' => $categories_level]);
    }
  

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories_item = ImgCat::findOrFail($id);
        $categories = ImgCat::all();
        $categories_level = ImgCat::where(['parent_id' => 0])->get();
        return view('admin.img_cat.edit',compact('categories', 'categories_item', 'categories_level'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->get("id");
        $result = false;

        if ($id == 0) {
            $post_data = $request->all();
            $categories = new ImgCat();            
            $categories->name = $post_data['name'];             
            $categories->description = $post_data['description'];
            $categories->parent_id = $post_data['parent_id'];
            $categories->published = $request->published ? $request->published : 0;
            $result = $categories->save();
        }else {
            $categories = ImgCat::findOrFail($id); 
            if($categories) {
                $categories->published = $request->published ? $request->published : 0;                  
                $result = $categories->update($request->all());
            } 
        }
        if ($result) {
            Session::flash('success', 'Categories saved successfully!');
        } else {
            Session::flash('error', 'Categories failed to save successfully!');
        }
        if ($categories && $categories->id) {
            return redirect()->route('image.editCat', ['cat_id' => $categories->id]); 
        }
        return redirect()->route('image.createCat' );;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function delete($id)
    {
        $categories = ImgCat::find($id);
        $categories->delete();
        return redirect()->route('image.cats');
    }
}
