<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ListeningCat;
use App\Models\ListeningDialog;
use App\Models\ListeningQuestion;


class ListeningFrontController extends Controller
{
    // public function __construct() {
    //     $this->middleware('auth');
    // }
    public function index()
    {
        $cats = ListeningCat::orderBy('id', 'asc')->paginate(50);       
        return view('front.listenCats', ['cats' => $cats]);
    }
    public function dialogs($id) {
        $dialogs = ListeningCat::find($id)->dialogs()->orderBy('id')->paginate(20);
        // echo '<pre>'; var_dump($dialogs);  echo '</pre>';
        return view('front.listenDialogs', compact('dialogs'));
    }
    public function test($id) {
        $dialogs = ListeningDialog::find($id);
        $questions = ListeningDialog::find($id)->questions();
        // echo '<pre>'; var_dump($questions);  echo '</pre>';
        return view('front.listenTest', compact('dialogs', 'questions'));
    }
}
