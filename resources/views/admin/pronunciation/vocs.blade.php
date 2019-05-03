@extends('layouts.admin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{$cat->title}}</h2>
         
    </div>
    <div class="col-lg-2">
        <br>
        <br>
        
    </div>
</div>
<br>
<div id="home_categories" >
    <div class="ibox-content">
        <a href="{{ url('admin/pronunciation/create_voc')}}" type="button" class="btn btn-primary btn-lg">Add new Voc</a>
        <div class="table-responsive">
        <table class="table table-stripped  ">
            <thead>
                <tr>
                    <th style="width: 35px">N.0</th>
                    <th >Id</th>
                    <th >English</th>
                    <th >Pinyin</th>
                     <th >Numb Like</th>
                     
                    <th >&nbsp;</th>
                </tr>
                </tr>
            </thead>
            <tbody>  
                
                @foreach ($vocs as $i => $category)
                <tr>	
                    <td > {{$i+1}} </td>

                    <td > {{$category->id}} </td>
                    <td ><a href="{!! URL::route('pronunciation.edit_voc', $category->id)!!}">{!!$category->english!!}</a> </td>
                    <td > {{$category->pinyin}} </td>
                    <td > {{$category->numb_like}} </td>
                     
                    <td style="width: 162px;">
                        <a href="{{ URL::route('pronunciation.edit_voc', $category->id) }}" class="btn btn-info">Update</a>
                       @if(!$category->in_drawable)
                        <a href="{{ URL::route('pronunciation.delete_voc', $category->id) }}" class="btn btn-danger">Delete</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="menu_pagination"> </div>
        </div>
    </div>
</div>

@endsection
@section("content_js")
<script src="{!! asset('assets/js/plugins/dataTables/datatables.min.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/dataTables/datatables.min.css')!!}" rel="stylesheet">
<style>
    tr.title{
        background:  #2f4050;
        font-weight: bold;
        color: #fff;
        padding-top: 10px;
    }
    tr.title td{
        
        padding-top: 20px !important;
    }
    </style>
<script>
 

</script>
@endsection
