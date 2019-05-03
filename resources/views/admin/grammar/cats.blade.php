@extends('layouts.admin')

@section('content')
 
<div id="home_categories" >
    <div class="ibox-content">
        <a href="{!! URL::route('grammar.create_cat')!!}" type="button" class="btn btn-primary btn-lg">Add new Categories</a>
        <div class="table-responsive">
        <table class="table table-stripped  ">
            <thead>
                <tr>
                      <th >Id</th>
                    <th >Title</th>
                    <th >Alias</th>
                     <th >Thumb Nail </th>
                     
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
                    <td ><a href="{!! URL::route('grammar.lessons', $category->id)!!}">{{$category->title}}</a> </td>
                    <td > {{$category->title_vi}} </td>
                    <td > {{$category->description}} </td>
                    
                    <td style="width: 262px;">
                        <a href="{{URL::route('grammar.list_cat_question', $category->id) }}" class="btn btn-info">Questions</a>
                        <a href="http://ocodereducation.com/admin/listquestion/list/gr-{{$category->id}}" class="btn btn-warning" target="_blank">Questions Old</a>
                      
                         
                        <a href="{{ URL::route('grammar.edit_cat', $category->id) }}" class="btn btn-info">Update</a>
                        @if( !$category->in_drawable)
                        <a href="{{URL::route('grammar.delete_cat', $category->id) }}" class="btn btn-danger delete-cat">Delete</a>
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
<script src="{!! asset('assets/js/plugins/sweetalert/sweetalert.min.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/sweetalert/sweetalert.css')!!}" rel="stylesheet">
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
 
$(".delete-cat").click(function(){
    that = this;     
    swal({
        title: "Are you sure?",
        text: "Category này sẽ bị xóa vĩnh viễn!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, Delete!",
        closeOnConfirm: false
    }, function (is_confirm) {
        if(is_confirm)
            window.location = $(that).attr("href");
          
    });
    return false;
});
</script>
@endsection
