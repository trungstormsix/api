@extends('layouts.admin')

@section('content')
<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/user/save') }}">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Edit User</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin')}}">Home</a>
                </li>
                <li>
                    <a href="{{url('/admin/users')}}">User</a>
                </li>
                <li class="active">
                    <strong>Edit User</strong>
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
    <input type="hidden" name="id" value="{{empty($user) ? old('id') : $user->id}}" />
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">                
                <div class="ibox-content">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Username
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='username' value="{{old('title') ? old('title') : $user->username }}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Email
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='email' value="{{old('title') ? old('title') : $user->email }}">
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
                            Roles
                        </label>
                        <div class="col-sm-10">
                            @foreach($roles as $role)
                            
                            <label><input type="checkbox" name="roles[]"  {{$user->hasRole($role->name) ?  'checked' : '' }} value="{{$role->id}}">{{$role->display_name}}</label><br>
                             @endforeach

                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                </div>
            </div>
        </div>
    </div>
</form>

@endsection