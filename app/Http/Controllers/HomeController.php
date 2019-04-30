<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Models\FaceAds;
use App\Models\FaceCron;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (Auth::check()) {
            return view('home');
        }
        return view('welcome');
    }

    public function xlm() {
        $coin = strtoupper(\Illuminate\Support\Facades\Input::get("coin", "XLM"));
       
        $data = @file_get_contents("https://api.binance.com/api/v1/trades?symbol=" . $coin . "BTC");
        $binance_data = json_decode($data);
        $binance = new \stdClass();
        $binance->buy_qty = 0;
        $binance->sell_qty = 0;
        $binance->buy_btc = 0;
        $binance->sell_btc = 0;
        if ($binance_data) {
            $binance_data = array_reverse($binance_data);
            foreach ($binance_data as $a) {
 
                if (!$a->isBuyerMaker) {
                    $binance->buy_qty += $a->qty;
                    $binance->buy_btc += $a->qty * $a->price;
                } else {
                    $binance->sell_qty += $a->qty;
                    $binance->sell_btc += $a->qty * $a->price;
                }
            }
        } else {
            $binance_data = array();
        }
        $bittex_d = file_get_contents("https://bittrex.com/api/v1.1/public/getmarkethistory?market=BTC-" . strtoupper($coin) . "&type=both");
        $bittex_data = json_decode($bittex_d);
        $bittrex = new \stdClass();
        $bittrex->buy_qty = 0;
        $bittrex->buy_btc = 0;
        $bittrex->sell_qty = 0;
        $bittrex->sell_btc = 0;
        if ($bittex_data->success) {
            foreach ($bittex_data->result as $a) {
                if ($a->OrderType == "BUY") {
                    $bittrex->buy_qty += $a->Quantity;
                    $bittrex->buy_btc += $a->Quantity * $a->Price;
                } else {
                    $bittrex->sell_qty += $a->Quantity;
                    $bittrex->sell_btc += $a->Quantity * $a->Price;
                }
            }
        }
        $refresh = 600;
        return view('trade.total', compact('binance_data', 'binance', 'bittrex', 'coin', 'refresh'));
    }

    public function boll() {
//        $coin = strtoupper(\Illuminate\Support\Facades\Input::get("coin", "XLM"));
//        if($coin){
//            
//        }
        $coins = new \stdClass();
        $coins->XVG = false;

        $coins->POE = false;
        $coins->XLM = false;
        $coins->BTS = false;
        $coins->BQX = false;
        $coins->VIBE = false;
        $coins->ZRX = false;
        $coins->BNT = false;
        $coins->BNB = false;
        $coins->TNT = false;
        $coins->ICX = false;
        $coins->XRP = false;
        $coins->ADA = false;

        foreach ($coins as $c => &$val) {
            $val = ($this->buy($c));
        }
        $refresh = 900;
        $coin = "Kèo Hồi";
        return view('trade.bolls', compact('coins', 'refresh', 'coin'));
    }

    function buy($coin) {
        $coin = strtoupper($coin);
        $data_string = file_get_contents("https://api.binance.com/api/v1/klines?symbol=" . $coin . "BTC&interval=30m&limit=20");
        $price_str = file_get_contents("https://api.binance.com/api/v1/depth?symbol=" . $coin . "BTC&limit=5");

        $price = json_decode($price_str);

        $ask = $price->asks;
        $bid = $price->bids;

        $data = json_decode($data_string);
        $close = array();
        foreach ($data as $d) {
            $close[] = $d[4];
        }
        $boll = $this->calculateBoll($close);

//        echo " ask:". $close[0] . " boll:". $boll->low. " ";
        if ($boll->low >= $ask[0][0] || $boll->low >= $bid[0][0]) {
            $boll->buy = true;
        } else {
            $boll->buy = false;
        }
        $boll->highest_bid = $ask[0][0];
        return $boll;
    }

    function calculateBoll($array, $count = 2) {
        $return = new \stdClass();

        $MA20 = array_sum($array) / count($array);
        $return->MA20 = number_format($MA20, 8);
        $std = $this->standard_deviation($array);
        $return->low = $MA20 - ($std * $count);
        $return->high = $MA20 + ($std * $count);
        if ($count != 3) {

            $return->boll3_low = $MA20 - ($std * 3);
            $return->boll3_high = $MA20 + ($std * 3);
        }
        return $return;
    }

    function standard_deviation($aValues, $bSample = false) {
        $fMean = array_sum($aValues) / count($aValues);
        $fVariance = 0.0;
        foreach ($aValues as $i) {
            $fVariance += pow($i - $fMean, 2);
        }
        $fVariance /= ( $bSample ? count($aValues) - 1 : count($aValues) );
        return (float) sqrt($fVariance);
    }
	
	public function saveAd(){      
		date_default_timezone_set('Pacific/Honolulu');	
        $ad_id = Input::get("ad_id");
        $purchase = Input::get("purchase");
        $date = (new \DateTime())->format('Y-m-d');
        $old = $purchase;
        $faceAd = FaceAds::where("ad_id", $ad_id)->where("date", $date)->first();
        if(!$faceAd){
            FaceAds::where("ad_id", $ad_id)->delete();
            $faceAd = new FaceAds();
            $faceAd->ad_id = $ad_id;
            $faceAd->purchase = $purchase;
            $faceAd->date = $date;
            $faceAd->save();
        }else{
           
            $old = $faceAd->purchase;
            $faceAd->purchase = $purchase;
            $faceAd->date = $date;
            $faceAd->save();
        }
        return $old;
    } 
	
	public function saveCron() {
		date_default_timezone_set('Pacific/Honolulu');
        $faceAd = new FaceCron();
        $faceAd->date = (new \DateTime())->format('Y-m-d H:i:s');
        $faceAd->save();
    }
}
