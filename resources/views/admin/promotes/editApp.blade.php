@extends('layouts.admin')

@section('content')
<!-- header -->
<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/promote/app') }}">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{empty($title) ?  'oCoder' : $title}}</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>

                <li class="active">
                    <strong>Edit App</strong>
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
    @if (Session::has('success'))
    <br>
    <div class="alert alert-success alert-dismissable animated fadeInDown">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        {{ Session::get('success') }}
    </div>
    
    @elseif (Session::has('error'))
    <br>
    <div class="alert alert-danger  alert-dismissable animated fadeInDown">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        {{ Session::get('error') }}
    </div>

    @endif
    
    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{empty($app) ? old('id') : $app->id}}" />
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">                
                <div class="ibox-content">
                    
                    @if($app)
                     <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Title
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='title' value="{{old('title') ? old('title') : $app->title }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            <img alt="{{$app->title}}" style="max-width: 130px  " class="img-circle circle-border" src="http://ocodereducation.com{{$app->image}}">
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='image' value="{{old('image') ? old('image') : $app->image }}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Status
                        </label>
                        <div class="col-sm-10">
                            <input class="js-switch" style="display: none;" data-switchery="true" type="checkbox" name="status" {{(old('status') || $app->status) ? 'checked' : '' }} >
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    @endif
                     <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Cat
                        </label>
                        <div class="col-sm-10">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="input-sm form-control" name="publish_up" value="{{$app ? date("Y-m-d",strtotime($app->publish_up)) : ''}}">
                                <span class="input-group-addon">to</span>
                                <input type="text" class="input-sm form-control" name="publish_down" value="{{$app ? date("Y-m-d",strtotime($app->publish_down)) : ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Publish
                        </label>
                        <div class="col-sm-10">
                            <select class="form-control m-b" name="group_id">'
                                @foreach($groups as $group)
                                <option {{($app && $app->group_id == $group->id) ? "selected" : (Session::get('group_id')  == $group->id ? "selected" :"")}} value='{{$group->id}}'>
                                    {{$group->title}}
                                </option>
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
    
    $('.input-daterange').datepicker({
       keyboardNavigation: false,
       forceParse: false,
       autoclose: true,
       format: "yyyy-mm-dd"

    });
 </script>
@endsection