@extends('layouts.admin')

@section('content')
@php( $category = isset($category) ? $category : false)

<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/makeup/cat/update') }}">

    <input  type="hidden" name='id' value="{{ $category ? $category->id : '' }}">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{ $category ? "Edit" : 'Create' }} Categories</h2>
            
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin')}}">Home</a>
                </li>
                <li>
                    <a href="{{url('/admin/makeup/cat')}}">Categories</a>
                </li>
                <li class="active">
                    <strong>{{ $category ? "Edit" : 'Create' }} Categories</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <br>
            <br>
            <div class="pull-right tooltip-demo">
                <button  class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new Categories"><i class="fa fa-plus"></i> Save</button>
                <a href="{{url('/admin/makeup/cat')}}" class="btn btn-danger btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cancel Edit"><i class="fa fa-times"></i>Discard</a>
            </div>
        </div>
    </div>
    

    {{ csrf_field() }}
    <!--input type="hidden" name="id" value="{{empty($user) ? old('id') : $user->id}}" /-->
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">                
                <div class="ibox-content">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Title
                        </label>
                        <div class="col-sm-10">
	                    	<input class="form-control" type="text" name='title' value="{{old('title') ? old('title') : ($category ? $category->title : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
<div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Title Display
                        </label>
                        <div class="col-sm-10">
	                    	<input class="form-control" type="text" name='title_display' value="{{old('title_display') ? old('title_display') : ($category ? $category->title_display : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Description
                        </label>
                        <div class="col-sm-10">
	                    	<input class="form-control" type="text" name='description' value="{{old('description') ? old('description') : ($category ? $category->description : '') }}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                      
 
                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Published
                        </label>
                        <div class="col-sm-10">
                            <input class="js-switch" value="1" style="display: none;" data-switchery="true" type="checkbox" name="published" {{(old('published') || $category == false || ($category && $category->published)) ? 'checked' : '' }} >
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>   
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section("content_js")

<script>
    
    var elem = document.querySelector('.js-switch');
    var switchery = new Switchery(elem, {color: '#1AB394'});
</script>
@endsection