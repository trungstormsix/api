@extends('layouts.admin')

@section('content')
 <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Search Word</h2>

    </div>
    <div class="col-lg-2">
        <br>
        <br>
           <form type="GET" action="{!! url('admin/youtube/search-playlists') !!}">
             <input name='search' value='{{ @$search }}' placeholder="Search Playlist..." required class='form-control'/>
         </form>
    </div>
</div>
<div id="home_categories" >
    <div class="ibox-content">
        <a href="{{ url('admin/youtube/cat/en-add')}}" type="button" class="btn btn-primary btn-lg">Add new Categories</a>
        <div class="table-responsive">
        <table class="table table-stripped  ">
            <thead>
                <tr>
                    <th >Id</th>
                    <th >Title</th>
                     
                    <th >&nbsp;</th>
                </tr>
                </tr>
            </thead>
            <tbody>  
                @php($t = "")
                @foreach ($cats as $category)
                <tr class='{{$t == $category->pcat ? "" : "title" }}'>	
                    @php($t = $category->pcat)
                    <td > {{$category->id}} </td>
                    <td ><a href="{!! url('admin/youtube/en-playlists', $category->id)!!}">{{$category->title}}</a> </td>
                     
                    <td style="width: 262px;"> 
                        <a href="{{ url('admin/youtube/cat/en-edit', $category->id) }}" class="btn btn-info">Update</a>
                        @if(!$category->in_drawable)
                        <!--<a href="{{URL::route('pronunciation.delete_cat', $category->id) }}" class="btn btn-danger">Delete</a>-->
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
