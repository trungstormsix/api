@extends('layouts.admin')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>{{empty($title) ?  'oCoder' : $title}}</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
            </li>

            <li class="active">
                <strong>Playlists</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
        <!--        <br>
                <br>
                <div class="pull-right tooltip-demo">
                    <a href="{{url('/admin/playlist/add')}}" class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new playlist"><i class="fa fa-plus"></i> Add</a>
                    <a href="#" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Discard email"><i class="fa fa-times"></i> Discard</a>
                </div>-->
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
<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Profile</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/profile') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label"> E-Mail Address</label>

                                <div class="col-sm-10">
                                    {{$user->email}}
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">  Password</label>

                                <div class="col-sm-10">
                                    <input id="password" type="password" class="form-control" name="password">

                                    @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">   
                                    New password
                                </label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name='new_password' value="">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">   
                                    Confirm password
                                </label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name='confirm_password' value="">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-sign-in"></i> Save
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
