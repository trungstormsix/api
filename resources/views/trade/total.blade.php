@extends('layouts.front')

@section('content')
<div class="row features">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="col-lg-8 text-center">
                <h1>Recent Total {{$coin}}</h1>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                <form>
                    <div class="input-group">
                        <input class="form-control" required style="height: 42px;" name="coin" placeholder="ENTER COIN ABBR"  type="text"> 
                        <span class="input-group-btn"> <button type="submit"  class="btn btn-primary"><span class="fa fa-search"></span> </button> </span>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    <div class="ibox-content">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Market</th>
                        <th >Total Buy (BTC)</th>
                        <th >Total Sell (BTC)</th>
                        <th >Number Buy</th>
                        <th >Number Sell</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Bittrex</th>
                        <td {{$bittrex->buy_btc > $bittrex->sell_btc ? "class=bold" : "" }}>{{$bittrex->buy_btc}}</td>
                        <td {{$bittrex->buy_btc < $bittrex->sell_btc ? "class=bold" : "" }}>{{$bittrex->sell_btc}}</td>
                        <td>{{$bittrex->buy_qty}}</td>
                        <td>{{$bittrex->sell_qty}}</td>
                    </tr>
                    <tr>
                        <th>Binance</th>
                        <td {{@$binance->buy_btc > $binance->sell_btc ? "class=bold" : "" }}>{{$binance->buy_btc}}</td>
                        <td {{@$binance->buy_btc < $binance->sell_btc ? "class=bold" : "" }}>{{$binance->sell_btc}}</td>
                        <td>{{$binance->buy_qty}}</td>
                        <td>{{$binance->sell_qty}}</td>
                    </tr>
                </tbody>        

            </table>
        </div>
    </div>
    <h1>Binance recently orders</h1>
    <div class="ibox-content">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th >Quantity</th>
                        <th >Price</th>
                        <th>Total</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($binance_data as $data)
                    <tr class="{{$data->isBuyerMaker ? "sell" : "buy"}}">
                        <th>{{$data->isBuyerMaker ? "Sell" : "Buy"}}</th>
                        <td>{{$data->qty}}</td>
                        <td>{{$data->price}}</td>
                        <td>{{$data->qty * $data->price}}</td>
                        <td>{{date('Y-m-d H:i:s', $data->time/1000)}}</td>
                    </tr>
                    @endforeach
                </tbody>        

            </table>
        </div>
    </div>
</div>
</div>
<style>
    tr.buy{
        color: green;
    }
    tr.sell{
        color: red;
    }
    td.bold{
        font-weight: bold;
        color: red;
    }
</style>
@endsection