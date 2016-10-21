<?php

namespace App\library;

use App\Models\Ycat;

class Menus {

    public static function getCats() {
        $cats = Ycat::all();
        return $cats;
    }

}
