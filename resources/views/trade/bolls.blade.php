@extends('layouts.front')

@section('content')
<div class="row features">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="col-lg-12 text-center">
                <h1>Suggest Coins</h1>
            </div>
             
        </div>
    </div>

    <h1>Binance buy coins</h1>
    <div class="ibox-content">
        <div class="table-responsive">
            <table class="table dataTables-example">
                <thead>
                    <tr>
                        <th>Coin</th>
                        <th >price</th>
                        <th>boll</th>
                        <th >Buy</th>
                        <th>Buy Price</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($coins as $c => $val)
                    <tr class="{{$val->buy ? "buy" : ''}}">
                        <td><a href="{!! URL::route('trade.total',['coin'=>$c]) !!}" target="_blank">{{$c}}</a></td>
                        <td>{{$val->highest_bid}}</td>
                        <td>{{ number_format(floatval($val->low),8)}}</td>
                        <td>{{$val->buy}}</td>
                        <td>{{ number_format(floatval($val->boll3_low),8)}}</td>

                    </tr>
                    @endforeach
                </tbody>        

            </table>
        </div>
    </div>
</div>
</div>

@endsection

@section('content_script')
<style>
    tr.buy{
        color: green;
        font-weight: bold;
    }
    tr.sell{
        color: red;
    }
    td.bold{
        font-weight: bold;
        color: red;
    }
</style>
<script src="{!! asset('assets/js/plugins/dataTables/datatables.min.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/dataTables/datatables.min.css')!!}" rel="stylesheet">
<script>
        $(document).ready(function(){
            $('.dataTables-example').DataTable({
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [ 
                ],
                 "order": [[ 3, 'DESC' ], [ 0, 'asc' ]]

            });

             

        });

        
    </script>
@endsection
