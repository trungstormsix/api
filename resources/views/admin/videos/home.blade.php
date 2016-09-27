@extends('layouts.admin')

@section('content')
<!-- header -->
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{empty($title) ?  'oCoder' : $title}}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>

            <li class="active">
                <strong>Playlists of {{$cat->title}}</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
        <br>
        <br>
        <div class="pull-right tooltip-demo">
            <a href="{{url('/admin/playlist/add')}}" class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new playlist"><i class="fa fa-plus"></i> Add Playlist</a>
            <a href="{{url('/admin/ycat/edit/'.$cat->id)}}" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Discard email"><i class="fa fa-pencil"></i> Edit Cat</a>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox-content inspinia-timeline">
                @foreach ($playlists as $playlist)
                <div class="timeline-item">
                    <div class="row">
                        <div class="col-xs-1 date">
                            <i class="fa fa-youtube"></i>
                            {{$playlist->countVideos()->count}} videos
                            <br>
                            <!--<small class="text-navy">3 hour ago</small>-->
                        </div>

                        <div class="col-xs-11 content">
                            <div class="row">
                                <div class="col-xs-2">
                                    <img alt="{{$playlist->title}}" style="max-width: 120px  " class="img-circle circle-border" src="{{$playlist->thumb_url}}">
                                </div>  
                                <div class="col-xs-6">
                                    <a href="{{ url('/admin/videos/'.$playlist->id) }}"><h2 class="font-bold">{{$playlist->title}}</h2></a><br>
                                    {{$playlist->updated_at ? $playlist->updated_at : $playlist->created_at}}
                                </div>
                                <div class="col-xs-2">
                                    <a  href="https://www.youtube.com/playlist?list={{ $playlist->yid }}" class="btn btn-danger btn-sm" target="_blank"><i class="fa fa-youtube"></i> Youtube </a>
                                </div>
                                <div class="col-xs-2">
                                    <a href="{{ url('/admin/playlist/edit/'.$playlist->id) }}" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i> Edit </a>
                                </div>
                                 
                            </div>
                        </div>
                    </div>
                </div>                     
                @endforeach                    

            </div>
        </div>
    </div>
</div>
@endsection
