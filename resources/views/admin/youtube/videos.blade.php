@extends('layouts.admin')

@section('content')
<!-- header -->
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{empty($title) ?  'oCoder' : $title}}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/admin')}}">Home</a>
            </li>
            <li>
                <a href="{{url('/admin/youtube/playlists/'.$playlist->cat->id)}}">{{$playlist->cat->title}}</a>
            </li>
            <li class="active">
                <strong>{{$playlist->title}}</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
        <br>
        <div class="pull-right tooltip-demo">
            <a href="{{ url('admin/youtube/video/add')}}" type="button" class="btn btn-primary btn-lg">Add New Video</a>
        </div>



    </div>
</div>
<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">

            <div class="ibox-content inspinia-timeline">
                <div class="timeline-item">
                    <div class="row">
                        <div class="col-xs-1 date">
                            <b data-sort="time" class="sort">
                                Time
                                <span class="time fa fa-sort"></span>

                            </b>
                            <br>
                            <!--<small class="text-navy">2 hour ago</small>-->
                        </div>
                        <div class="col-xs-8 content no-top-border">
                            <div class="row">
                                <div class="col-xs-2">
                                </div>  
                                <div class="col-xs-7">
                                    <b data-sort="title" class="sort">
                                        Title
                                        <span class="title fa fa-sort"></span>

                                    </b>

                                </div>
                                <div class="col-xs-3">

                                </div>  
                            </div>
                        </div>
                        <div class="col-xs-2">
                        </div>
                        <div class="col-xs-1">
                            <b data-sort="updated_at" class="sort">
                                Updated
                                <span class="updated_at fa fa-sort"></span>

                            </b>
                        </div>
                    </div>
                </div>

                @foreach ($videos as $video)
                <div class="timeline-item">
                    <div class="row">
                        <div class="col-xs-1 date">
                            <i class="fa fa-briefcase"></i>
                            {{$video->time}}
                            <br>
                            <!--<small class="text-navy">2 hour ago</small>-->
                        </div>
                        <div class="col-xs-8 content no-top-border">
                            <div class="row">
                                <div class="col-xs-2">
                                    <img alt="{{$video->title}}" style="max-width: 130px  " class="img-circle circle-border" src="{{$video->thumb_url}}">
                                </div>  
                                <div class="col-xs-7">
                                    <a href="https://www.youtube.com/watch?v={{$video->yid}}" target="_blank"><h2 class="font-bold">{{$video->title}}</h2></a>
                                    {{$video->created_at}}
                                </div>
                                <div class="col-xs-3">
                                    <select data-id="{{$video->id}}" class="form-control m-b playlist">

                                        @foreach($playlist->cat->playlists()->orderBy("status","DESC")->orderBy("title","ASC")->get() as $pl)
                                        <option {{$pl->id == $playlist->id ? 'selected' : ""}} value="{{$pl->id}}">{{$pl->title.' ('.$pl->videos()->count().')'}}</option>

                                        @endforeach

                                    </select>
                                </div>  
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <a href="{{url('admin/youtube/video/edit/'.$video->id)}}" class="btn btn-success">Edit</a>
                            <button data-id="{{$video->id}}" class="btn btn-danger delete-video">Delete</button>
                        </div>
                        <div class="col-xs-1">
                            {{$video->updated_at}}
                        </div>
                    </div>
                </div>
                @endforeach     
            </div>

            {!! $videos->links() !!}
        </div>
    </div>
    <form id="sort">
        <input class="sort_by" name="sort_by" type="hidden" value="{{$sort_by}}" />
        <input class="sort_dimen"  name="sort_dimen" type="hidden" value="{{$sort_dimen}}" />

    </form>
</div>
@endsection
@section('content_js')
<script>
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{csrf_token()}}"
        }
    });
    jQuery(".delete-video").click(function () {
        id = jQuery(this).data("id");

        var that = this;
        jQuery.ajax({url: "{{url('admin/youtube/delete')}}", type: "POST", dataType: 'json', data: {id: id}}).done(function (data) {
            jQuery(that).parents(".timeline-item").delay(1000).remove();
        }).fail(function () {
            alert("error");
        });
    })

    jQuery(".playlist").change(function () {
        id = jQuery(this).data("id");
        var playlist_id = jQuery(this).val();
        var that = this;
        jQuery.ajax({url: "{{url('admin/youtube/change-playlist')}}", type: "POST", dataType: 'json', data: {id: id, playlist_id: playlist_id}}).done(function (data) {
            jQuery(that).parents(".timeline-item").addClass("btn btn-success").delay(2000).remove();
        }).fail(function () {
            alert("error");
        });
    })
</script>
@endsection