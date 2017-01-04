<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Models\Funny\Image;
use Illuminate\Support\Facades\Auth;

class ImagesController extends Controller {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
    }

    public function images() {
        $user = Auth::user();
        $query = Image::where("status", 1);
        $review = Input::get("review");

        if ($user && $user->hasRole("admin")) {
            if ($review == 1) {
                $query->orWhere("status", 3);
            } else if ($review == 2) {
                $query = Image::where("status", 3);
            }
        }
//          $query = Image::where("status", 3);
        $images = $query->orderBy("updated_at", "DESC")->orderBy("pic_date", "DESC")->paginate(15);
        foreach ($images as $image) {
            $likes = $image->likes()->count();

            $image->is_like = 0;
            $image->likes = $image->liked + $image->likes()->where("liked", 1)->count();
            if ($likes > 0 && $user) {
                $contain = $image->likes()->find($user->id);
                if ($contain) {
                    $image->is_like = $contain->pivot->liked;
                }
                if ($review == 2 && $image->status == 3) {
                    $image->is_like = -1;
                }
            }
        }
        return $images;
    }

    public function like() {
        if (!Auth::check()) {
            return response()->json([
                        'auth' => false,
                        'message' => "Please Login!"
            ]);
        }
        $user = Auth::user();

        $id = Input::get('id');

        $like = Input::get('like');
        if ($id != null) {
            $image = Image::find($id);
            if ($image->status != 2 && $image->status != 0) {
                if ($like != null && $like != 0) {
                    $image->likes()->syncWithoutDetaching([$user->id => ["liked" => $like]]);
                    if ($user->hasRole("admin")) {
                        if ($like != -1 && $image->status == 3) {
                            $image->status = 1;
                            $image->save();
                        } else if ($like == -1) {
                            $delete = Input::get('delete');
                            if ($delete == 1) {
                                $image->status = 0;
                            } else {
                                $image->status = 3;
                            }
                            $image->save();
                        }
                    }
                    return response()->json([
                                'auth' => true,
                                'result' => true,
                                'message' => "ok!"
                    ]);
                } else {
                    $image->likes()->detach([$user->id]);
                }
            }
        }
        return response()->json([
                    'auth' => true,
                    'result' => false,
                    'message' => "Some errore is occur!"
        ]);
    }

}
