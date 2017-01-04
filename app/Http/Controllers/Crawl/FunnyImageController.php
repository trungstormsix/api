<?php

namespace App\Http\Controllers\Crawl;

use App\Http\Controllers\Controller;
use File;
use Illuminate\Support\Facades\Session;
use App\library\DomParser;
use Illuminate\Support\Facades\Storage;
use App\library\OcoderHelper;

class FunnyImageController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
//        $this->middleware('auth');
    }

    var $count = 0;

    public function get9Gag() {
        $parser = new DomParser();
        $html = $parser->file_get_html("http://9gag.com");
        $articles = $html->find(".badge-entry-collection .badge-entry-container");
        foreach ($articles as $article) {
            $readMore = $article->find(".post-read-more", 0);
            if (!$readMore) {
                $title = trim(html_entity_decode($article->find("header h2", 0)->plaintext));
                $url = $article->find("header h2 a", 0)->href;
                $pic_date = date("Y-m-d", time());
                $image = $article->find(".post-container .badge-evt img", 0);
                if ($image) {
                    echo $image->outertext;
                    $img = $image->src;
                    //save image
                    $thumb = 'funny/' . $pic_date . '/' . OcoderHelper::getFileName($img);
                    $Image = \App\Models\Funny\Image::where("image", $thumb)->first();
                    if (!$Image) {
                        $width = 0;
                        if (!Storage::disk('images')->has($thumb)) {
                            @Storage::disk('images')->put($thumb, file_get_contents($img));
                        }
                        if (Storage::disk('images')->has($thumb)) {
                            if (Storage::disk('images')->size($thumb) > 200) {
                                $checkImg = \Intervention\Image\Facades\Image::make("images/" . $thumb);
                                $width = $checkImg->width();
                            }
                            if ($width < 200) {
                                Storage::disk('images')->delete($thumb);
                            }
                        }

                        $Image = new \App\Models\Funny\Image();
                        $Image->title = $title;
                        $Image->liked = rand(0, 30);
                        $Image->image = $thumb;
                        if (Storage::disk('images')->has($thumb)) {
                            $Image->status = 1;
                        } else if ($width == 0) {
                            $Image->status = 0;
                        }
                        $Image->url = $url;

                        $Image->pic_date = $pic_date;
                        $Image->save();
                        echo '<br>';
                    }
                }
            }
//            echo $article->find(".post-container",0)->innertext;
        }
        exit;
    }

    public function index() {
        if (@Storage::disk('xml')->has("crawl.json")) {
            $json = @Storage::disk('xml')->get("crawl.json");
            $object = json_decode($json);
        } else {
            $object = new \stdClass();
        }

        if (@$object->funny_page) {
            $page = $object->funny_page + 4;
        } else {
            $page = 7595;
        }
        
        do {
            $result = $this->_getImages("http://uberhumor.com/page/" . $page);
            if ($result) {
                $page--;
            } else {
                $page = 0;
            }
            $object->funny_page = $page;
            $json = @Storage::disk('xml')->put("crawl.json", json_encode($object));
        } while ($page != 0);
    }

    private function _getImages($link) {
        echo $link . '<br>';
        $parser = new DomParser();
        $html = $parser->file_get_html($link);
        $b = $html->find(".post .social-bookmark", 0);

        $images = $html->find('.post .homepostcontent');
        foreach ($images as $image_html) {
            $this->_getImage($image_html);
            if ($this->count > 3) {
                exit;
            }
        }
        if (sizeof($images) > 0) {
            return true;
        }
    }

    private function _getImage($html) {
        $title = html_entity_decode(trim($html->find(".homeposttitle", 0)->plaintext));
        $date = $html->find(".postdivider .date", 0)->plaintext . ' ' . $html->find(".postdivider .date_1", 0)->plaintext;
        if (strtotime($date . ' ' . date("Y")) > time()) {
            $date = $date . ' ' . (date("Y") - 1);
        }
        $pic_date = date("Y-m-d", strtotime($date));
        $is_cut = true;
        echo $pic_date . '<br>';

        $url = $html->find(".homeposttitle a", 0)->href;
        $liked = rand(0, 30); //$this->_getFaceShare($url);
        $img_html = $html->find(".homepagealin img", 0);
        if (!$img_html) {
            $img_html = $html->find("p img", 0);
            $is_cut = false;
        }
        if (!$img_html) {
            return;
        }
        $img = $img_html->src;
        //save image
        $thumb = 'funny/' . $pic_date . '/' . OcoderHelper::getFileName($img);
        $raw = 'funny/raw/' . OcoderHelper::getFileName($img);
        $Image = \App\Models\Funny\Image::where("image", $thumb)->first();
        if (!$Image) {
            $width = 0;
            if (!Storage::disk('images')->has($raw)) {
                @Storage::disk('images')->put($raw, file_get_contents($img));
            }
            if (Storage::disk('images')->has($raw) && Storage::disk('images')->size($raw) > 200) {
                if (!Storage::disk('images')->has("funny/" . $pic_date)) {
                    mkdir("images/funny/" . $pic_date);
                }
                $width = $this->_cutImage("images/" . $raw, "images/" . $thumb, $is_cut);
            }
            $Image = new \App\Models\Funny\Image();
            $Image->title = $title;
            $Image->liked = $liked;
            $Image->image = $thumb;
            if (Storage::disk('images')->has($thumb)) {
                $Image->status = 1;
            } else if ($width == 0) {
                $Image->status = 0;
                $Image->url = $url;
            } else {
                $Image->status = 2;
                $Image->url = $url;
            }
            $Image->url = $url;

            $Image->pic_date = $pic_date;
            $Image->save();
            $this->count++;
        }
    }

    private function _getFaceShare($url) {
        return 0;
        $json = @file_get_contents("http://graph.facebook.com/?id=$url");
        $face = json_decode($json);
        return @$face->share->share_count;
    }

    private function _cutImage($source_image, $fixed_image, $is_cut) {
        $img = \Intervention\Image\Facades\Image::make($source_image);
        $width = $img->width();
        if ($width < 300) {
            return $width;
        }
        if (!$is_cut) {
            copy($source_image, $fixed_image);
            return $width;
        }
        if ($is_cut) {
            $height = $img->height() - 41;
            $width = $img->width();
            $img->crop($width, $height, 0, 0);
        }
        $img->save($fixed_image);
        return $width;
    }

}
