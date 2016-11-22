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
                <a href="{{url('admin/idioms')}}">Idioms</a>
            </li>
            <li class="active">
                <strong>{{$idiom->word}}</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
        <br>
        <br>
        <div class="pull-right tooltip-demo">
            <!--<a href="{{url('/admin/playlist/add')}}" class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new playlist"><i class="fa fa-plus"></i> Add Playlist</a>-->
        </div>
    </div>
</div>
<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/idiom/add') }}">

    <div class="wrapper wrapper-content animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">                
                    <div class="ibox-content">
                        <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                Id
                            </label>
                            <div class="col-sm-10">
                                {{$idiom->id}}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                word
                            </label>
                            <div class="col-sm-10">
                                <input autocomplete="off" id="idiom_word" data-id='{{$idiom->id}}' value="{{$idiom->word}}" />
                                {{$idiom->word}} </div>     
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Mean
                            </label>
                            <div class="col-sm-10">
                                {!!$idiom->mean!!}
                            </div>
                        </div>       
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Example
                            </label>
                            <div class="col-sm-10">
                                {{$idiom->example}}
                                @if($idiom->example)
                                @foreach ($idiom->examples() as $example)
                                    <p>{{$example->id}} : {{$example->example}}</p>
                                @endforeach
                                @endif
                            </div>
                        </div>        
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Status
                            </label>
                            <div class="col-sm-10">
                                <span class="switchery" {!! ($idiom->published == 1) ? 'style="background-color: rgb(26, 179, 148); border-color: rgb(26, 179, 148); box-shadow: rgb(26, 179, 148) 0px 0px 0px 16px inset; transition: border 0.4s, box-shadow 0.4s, background-color 1.2s;"' : '' !!}><small {!! ($idiom->updated == 1) ? 'style="left: 20px; transition: left 0.2s;"' : '' !!}></small></span>
                            </div>
                        </div>         
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                updated
                            </label>
                            <div class="col-sm-10">
                                {{$idiom->updated}}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>>
@endsection

@section('search_form')
<form role="search" class="navbar-form-custom" action="{{url('admin/idioms/search')}}">
    <div class="form-group">
        <input type="text" placeholder="Search an idiom..." class="form-control" name="idiom" value="{{!empty($search) ? $search : ""}}" id="top-search">
    </div>
</form>
@endsection
 
@section('content_js')
<script>
    var elem = document.querySelector('.js-switch');
    var switchery = new Switchery(elem, {color: '#1AB394'});
    var linkIdiomWord = "{{url('admin/idiom/ajax-change-word')}}";

</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link href="{!! asset('assets/css/plugins/summernote/summernote.css')!!}" rel="stylesheet">
<link href="{!! asset('assets/css/plugins/summernote/summernote-bs3.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/plugins/summernote/summernote.min.js') !!}"></script>

@endsection