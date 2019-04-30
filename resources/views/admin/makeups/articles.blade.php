@extends('layouts.admin')

@section('content')

<style type="text/css">
    .table > tbody > tr > td {
        word-break: break-all;
    }
</style>
<h1>{{$cat ? $cat->title : ""}}</h1>
<div id="home_articles" >
    <div class="ibox-content">
        <a href="{{ url('admin/makeup/article/create')}}" type="button" class="btn btn-primary btn-lg">Add new Post</a>
        <table class="table">
            <thead>
                <tr>
                    <th >Id</th>
                    <th >Title</th>
                    <th >Thumbnail</th>
                    <th >Link</th>
                    <th >Content</th>
                    <th >Categories_id</th>
                    <th >published</th>
                    <th >Updated_at</th>
                    <th >&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($articles as $post)
                <tr>				
                    <td > {{$post->id}} </td>
                    <td ><a href="{{ url('admin/makeup/article/edit/'. $post->id) }}" target='_blank'>{{$post->title}}</a></td>
                    <td ><img src="{{strpos($post->intro_img,"http://") !== false ? "" : "http://android.ocodereducation.com/lamdep/"}}{{$post->intro_img}}" width="50px" /> </td>
                    <td ><a target="_blank" href="{{$post->link}}">Link</a> </td>
                    <td > {{ str_limit(strip_tags($post->content),50, '...')}} </td>
                    <td > {{$post->categories_id}} </td>
                    <td >     
                       
                        <a href="{{ url('admin/makeup/article/publish/' . $post->id.'?page='.$articles->currentPage()) }}" >
                            <span class="switchery" {!! ($post->published == 1) ? 'style="background-color: rgb(26, 179, 148); border-color: rgb(26, 179, 148); box-shadow: rgb(26, 179, 148) 0px 0px 0px 16px inset; transition: border 0.4s, box-shadow 0.4s, background-color 1.2s;"' : '' !!}><small  {!! ($post->published == 1) ? 'style="left: 20px; transition: left 0.2s;"' : '' !!} ></small></span>
                        </a>
                    </td>
                    <td > {{$post->date_edit}} </td>
                    <td style="width: 162px;">
                        <a href="{{ url('admin/makeup/article/edit/'. $post->id) }}" class="btn btn-info">Update</a>
                        <a href="{{ url('admin/makeup/article/delete/' . $post->id.'?page='.$articles->currentPage()) }}" class="btn btn-danger">Delete</a>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="menu_pagination">{{$articles->links()}}</div>
    </div>
</div>

@endsection