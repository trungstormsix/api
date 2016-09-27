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
            <li>
                <a href="{{url('/')}}">playlists</a>
            </li>
            <li class="active">
                <strong>{{$playlist->title}}</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">


    </div>
</div>
<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox-content inspinia-timeline">
                @foreach ($videos as $playlist)
                <div class="timeline-item">
                    <div class="row">
                        <div class="col-xs-3 date">
                            <i class="fa fa-briefcase"></i>
                            {{$playlist->time}}
                            <br>
                            <!--<small class="text-navy">2 hour ago</small>-->
                        </div>
                        <div class="col-xs-9 content no-top-border">
                            <div class="row">
                                <div class="col-xs-2">
                                    <img alt="{{$playlist->title}}" style="max-width: 130px  " class="img-circle circle-border" src="{{$playlist->thumb_url}}">
                                </div>  
                                <div class="col-xs-9">
                                    <a href="https://www.youtube.com/watch?v={{$playlist->yid}}" target="_blank"><h2 class="font-bold">{{$playlist->title}}</h2></a>
                                    {{$playlist->updated_at ? $playlist->updated_at : $playlist->created_at}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach     
            </div>
        </div>
    </div>
    @endsection
