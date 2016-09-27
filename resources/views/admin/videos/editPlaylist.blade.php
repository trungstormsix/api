@extends('layouts.admin')

@section('content')
<!-- header -->
<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/playlist/add') }}">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{empty($title) ?  'oCoder' : $title}}</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
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
                <a href="{{url('/admin/')}}" class="btn btn-danger btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cancel Edit"><i class="fa fa-times"></i> Discard</a>
            </div>
        </div>
    </div>

    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{empty($playlist) ? old('id') : $playlist->id}}" />
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">                
                <div class="ibox-content">
                    <div class="form-group"><label class="col-sm-2 control-label">Playlist ID</label>
                        <div class="col-sm-10"><input class="form-control" type="text" name='yid' value="{{empty($playlist) ? old('yid') : $playlist->yid}}"></div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    @if($playlist)
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            <img alt="{{$playlist->title}}" style="max-width: 130px  " class="img-circle circle-border" src="{{$playlist->thumb_url}}">
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='thumb_url' value="{{old('thumb_url') ? old('thumb_url') : $playlist->thumb_url }}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Status
                        </label>
                        <div class="col-sm-10">
                            <input class="js-switch" style="display: none;" data-switchery="true" type="checkbox" name="status" {{(old('status') || $playlist->status) ? 'checked' : '' }} >
                        </div>
                    </div>
                    @endif


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