@extends('layouts.admin')

@section('content')
<!-- header -->
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-7">
        <h2>{{empty($title) ?  'oCoder' : $title}}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/admin')}}">Home</a>
            </li>

            <li class="active">
                Playlists of <strong>{{$cat->title}}</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
        <br>
        
           <form type="GET" action="{!! url('admin/youtube/search-playlists') !!}">
             <input name='search' value='{{ @$search }}' placeholder="Search Playlist..." required class='form-control'/>
         </form>
    </div>
    <div class="col-lg-3">
        <br>
   
     
        <div class="pull-right tooltip-demo">
            <a href="{{url('/admin/youtube/playlist/add')}}" class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add playlist"><i class="fa fa-plus"></i> Add Playlist</a>
            <a href="{{url('/admin/youtube/cat/edit/'.$cat->id)}}" class="btn btn-info btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Discard email"><i class="fa fa-pencil"></i> Edit Cat</a>
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
                            {{$playlist->countVideos() ? $playlist->countVideos()->count : ""}} videos
                            <br>
                            <!--<small class="text-navy">3 hour ago</small>-->
                        </div>

                        <div class="col-xs-11 content">
                            <div class="row">
                                <div class="col-xs-2">
                                    <img alt="{{$playlist->title}}" style="max-width: 120px  " class="img-circle circle-border" src="{{$playlist->thumb_url}}">
                                </div>  
                                <div class="col-xs-6">
                                    <a href="{{ url('/admin/youtube/videos/'.$playlist->id) }}"><h2 class="font-bold">{{$playlist->title}}</h2></a><br>
                                    {{$playlist->updated_at ? $playlist->updated_at : $playlist->created_at}}
                                </div>
                                <div class="col-xs-1">
                                    <a  href="https://www.youtube.com/playlist?list={{ $playlist->yid }}" class="btn btn-danger btn-sm" target="_blank"><i class="fa fa-youtube"></i> Youtube </a>
                                </div>
                                <div class="col-xs-1" style="text-align: right">
                                     View:<br>
                                     <b>{{number_format($playlist->view_count)}}</b>
                                 </div>
                                <div class="col-xs-1">
                                    <span class="switchery" {!! ($playlist->status == 1) ? 'style="background-color: rgb(26, 179, 148); border-color: rgb(26, 179, 148); box-shadow: rgb(26, 179, 148) 0px 0px 0px 16px inset; transition: border 0.4s, box-shadow 0.4s, background-color 1.2s;"' : '' !!}><small {!! ($playlist->status == 1) ? 'style="left: 20px; transition: left 0.2s;"' : '' !!}></small></span>
                                </div>

                                <div class="col-xs-1">
                                    <a href="{{ url('/admin/youtube/playlist/edit/'.$playlist->id) }}" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i> Edit </a>
                                    <a href="{{ url('/admin/youtube/playlist/crawl-videos/'.$playlist->id) }}" target="_blank" class="btn btn-white btn-sm"><i class="fa fa-download"></i> Crawl </a>
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
