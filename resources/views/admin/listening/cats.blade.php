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
                <strong>Idioms</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
        <br>
        <br>
        <div class="pull-right tooltip-demo">
            <a href="{{url('/admin/listening/add-cat')}}" class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new playlist"><i class="fa fa-plus"></i> Add Playlist</a>
         </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox-content inspinia-timeline">
                @foreach ($cats as $cat)
                <div class="timeline-item">
                    <div class="row">
                        <div class="col-xs-1 date">
                            <i class="fa fa-youtube"></i>
                            {{$cat->dialogs()->count()}} Lessons
                            <br>
                            <small class="text-navy">{{$cat->dialogs()->where('status',1)->count()}} Published</small>
                        </div>

                        <div class="col-xs-11 content">
                            <div class="row">
                                
                                <div class="col-xs-6">
                                    <a href="{{ url('/admin/listening/cat/'.$cat->id) }}"><h2 class="font-bold">{{$cat->title}}</h2></a><br>
                                 </div>
                                  
                                
                                <div class="col-xs-1">
                                    <a href="{{ url('/admin/listening/edit-cat/'.$cat->id) }}" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i> Edit </a>
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

@section('search_form')
<form role="search" class="navbar-form-custom" action="{{url('admin/listening/search')}}">
    <div class="form-group">
        <input type="text" placeholder="Search an idiom..." class="form-control" name="idiom" id="top-search">
    </div>
</form>
@endsection