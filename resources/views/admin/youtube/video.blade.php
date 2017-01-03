@extends('layouts.admin')

@section('content')
<!-- header -->
<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/youtube/video/save') }}">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{empty($title) ?  'oCoder' : $title}}</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin')}}">Home</a>
                </li>
                <li>
                    <a href="{{url('/admin/youtube/playlists/'.($playlist ? $playlist->cat_id : Session::get('cat_id')))}}">Playlists</a>
                </li>
                 <li>
                    <a href="{{url('/admin/youtube/videos/'.($playlist->id))}}">{{$playlist->title}}</a>
                </li>
                <li class="active">
                    <strong>Edit Playlist</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <br>
            <br>
            <div class="pull-right tooltip-demo">
                <button   class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new playlist"><i class="fa fa-plus"></i> Save</button>
                <a href="{{url('/admin/youtube/videos/'.($playlist->id))}}" class="btn btn-danger btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cancel Edit"><i class="fa fa-times"></i> Discard</a>
            </div>
        </div>
    </div>


    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{empty($video) ? old('id') : $video->id}}" />
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">                
                <div class="ibox-content">
                    <div class="form-group"><label class="col-sm-2 control-label">Video ID</label>
                        <div class="col-sm-10"><input class="form-control"  placeholder="Playlist Id" type="text" name='yid' value="{{empty($video) ? old('yid') : $video->yid}}"></div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group"><label class="col-sm-2 control-label">Video Title</label>
                        <div class="col-sm-10"><input class="form-control" placeholder="Title" type="text" name='title' value="{{empty($video) ? old('title') : $video->title}}"></div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    @if($video)
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            <img alt="{{$playlist->title}}" style="max-width: 130px  " class="img-circle circle-border" src="{{$video->thumb_url}}">
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='thumb_url' value="{{old('thumb_url') ? old('thumb_url') : $video->thumb_url }}">
                            <a href="https://www.youtube.com/watch?v={{$video->yid}}" target="_blank">Watch Online</a>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Time
                        </label>
                        <div class="col-sm-10">
                            <input name="time" value="{{old('time') ? old('time') : $video->time }}" />
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    @endif
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Playlist
                        </label>
                        <div class="col-sm-10">
                                 <select   class="form-control m-b playlist" name="playlist">

                                    @foreach($playlist->cat->playlists()->orderBy("status","DESC")->orderBy("title","ASC")->get() as $pl)
                                    <option {{$pl->id == $playlist->id ? 'selected' : ""}} value="{{$pl->id}}">{{$pl->title.' ('.$pl->videos()->count().')'}}</option>

                                    @endforeach

                                </select>
                         </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('content_js')
<script>
    var elem = document.querySelector('.js-switch');
    var switchery = new Switchery(elem, {color: '#1AB394'});

</script>
@endsection