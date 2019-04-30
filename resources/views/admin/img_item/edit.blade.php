@extends('layouts.admin')

@section('content')
@php( $articles = isset($articles) ? $articles : false)

<form class="form-horizontal" role="form" method="POST" action="{!! URL::route('image.saveImg')!!}">

    <input  type="hidden" name='id' value="{{ $articles ? $articles->id : '' }}">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>{{ $articles ? "Edit" : 'Create' }} Article</h2>


            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin')}}">Home</a>
                </li>
                <li>
                    <a href="{!! URL::route('image.cats')!!}">Images</a>
                </li>
                <li class="active">
                    <strong>{{ $articles ? "Edit" : 'Create' }} Article</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4">
            <br>
            <br>
            <div class="pull-right tooltip-demo">
                <input class="btn btn-primary dim" type="submit" id="save_and_new" name="save_and_new" value="Save and New" /> 
                <button  class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new Articles"><i class="fa fa-plus"></i> Save</button>
                <a href="{{ URL::previous() }}" class="btn btn-danger btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cancel Edit"><i class="fa fa-times"></i>Discard</a>
            </div>
        </div>
    </div>


    {{ csrf_field() }}
    <!--input type="hidden" name="id" value="{{empty($user) ? old('id') : $user->id}}" /-->
    <div class="row profile">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">                
                <div class="ibox-content">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Title
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='title' value="{{old('title') ? old('title') : ($articles? $articles->title : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                     

                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Thumbnail
                        </label>
                        <div class="col-sm-10">
                            @if($articles && $articles->thumb)
                            <div class="img-wrapper avatar-view" title="Change the avatar">
                                <img id="avt_image" src="{{$articles ? $articles->thumb : '/images/avatar1.jpg'}}" />  
                                <i class="fa fa-camera" data-toggle="modal" data-target="#profilePictureModal"></i> 
                            </div>
                            @endif
                            @php ($value = (old('thumb') ? old('thumb') : ($articles ? $articles->thumb : '')))
                            {!! App\library\OcoderHelper::GenerateIcon($value, 'thumb', URL::asset("/public/filemanager/index.html"), 'thumb') !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Main Image
                        </label>
                        <div class="col-sm-10">
                            @php ($value = (old('main_img') ? old('main_img') : ($articles ? $articles->main_img : '')))
                            {!! App\library\OcoderHelper::GenerateIcon($value, 'main_img', URL::asset("/public/filemanager/index.html"), 'main_img') !!}
                        </div>
                    </div>
                    
                    <div class="hr-line-dashed"></div>   

                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            @if($articles && $articles->link)
                            <a href="{!!old('link') ? old('link') : ($articles? $articles->link : '')!!}" target="blank">Link</a>
                            @else
                            Link
                            @endif
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='link' value="{!!old('link') ? old('link') : ($articles? $articles->link : '')!!}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>  
                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Description
                        </label>
                        <div class="col-sm-10">
                            <textarea rows="10" class="full-width" name="description">{!!(old('description') ? old('description') : ($articles ? $articles->description : ''))!!}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Category  
                        </label>
                        <div class="col-sm-10">
                            @php ($category_id = old('cat_id') ? old('cat_id') : ($articles ? $articles->cat_id : \Session::get('img_cat_id',1)))
                            <select name="cat_id" data-placeholder="Choose a Country..." class="chosen-select" style="width:350px;" tabindex="2">
                                <option value="0">none</option>	
                                @foreach ($categories_level as $categories_level_1)	
                                @if ($categories_level_1->id == $category_id)		
                                <option value="{{$categories_level_1->id}}" selected="selected">{{$categories_level_1->name}}</option>
                                @else		
                                <option value="{{$categories_level_1->id}}">{{$categories_level_1->name}}</option>
                                @endif
                                @foreach ($categories as $categories_level_2)
                                @if ($categories_level_2->parent_id == $categories_level_1->id)
                                @if ($categories_level_2->id == $category_id)	
                                <option value="{{$categories_level_2->id}}" selected="selected">&nbsp;&nbsp;&nbsp; {{$categories_level_2->name}}</option>
                                @else		
                                <option value="{{$categories_level_2->id}}">&nbsp;&nbsp;&nbsp; {{$categories_level_2->name}}</option>
                                @endif
                                @foreach ($categories as $categories_level_3)
                                @if ($categories_level_3->parent_id == $categories_level_2->id)
                                @if ($categories_level_3->id == $category_id)		
                                <option value="{{$categories_level_3->id}}" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$categories_level_3->name}}</option>
                                @else		
                                <option value="{{$categories_level_3->id}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$categories_level_3->name}}</option>
                                @endif
                                @endif
                                @endforeach
                                @endif
                                @endforeach			
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>     
                    

                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Published
                        </label>
                        <div class="col-sm-10">

                            <input class="js-switch" value="1" style="display: none;" data-switchery="true" type="checkbox" name="published" {{(old('published') || $articles == false || ($articles && $articles->published)) ? 'checked' : '' }} >

                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>   
                </div>
            </div>
        </div>
    </div>
</form>
<div class="modal inmodal" id="profilePictureModal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <form class="avatar-form" action="{!! URL::route('image.thumbImg') !!}" enctype="multipart/form-data" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{@$articles->id}}" />
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Profile Picture</h4>
                </div>
                <div class="modal-body">
                    @php($img = @$articles->thumb ? $articles->thumb : "")
                    <!-- Upload image and data -->
                    <div class="avatar-upload">
                        <input type="hidden" class="avatar-src" name="avatar_src" value="{{$img ? $img : '/images/avatar1.jpg'}}">
                        <input type="hidden" class="avatar-data" name="avatar_data">

                    </div>
                    <div class="img-center">
                        <div class="img-wrapper avatar-wrapper">
                            <img  src="{{$img ? $img : '/images/avatar1.jpg'}}" />

                        </div>

                    </div>
                    <div class="row zoom-row">
                        <div class="col-sm-1 small">
                            <i class="fa fa-image small"></i>
                        </div>
                        <div class="col-sm-10">
                            <div id="range_slider"></div>
                        </div>
                        <div class="col-sm-1">
                            <i class="fa fa-image big"></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white btn-close" data-dismiss="modal">Close</button>
                    <input type="file" class="avatar-input" id="avatarInput"   accept="image/*" name="avatar_file" >

                    <button type="button" class="btn btn-primary uploadimage" id="upload_avatar_btn">Upload Image</button>
                    <button type="submit" class="btn btn-primary avatar-save">Save</button>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection
@section("content_js")
  
<script src="{!! asset('assets/js/plugins/chosen/chosen.jquery.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/chosen/chosen.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/plugins/cropper/cropper.min.js') !!}"></script>
<link href="{!! asset('assets/js/plugins/cropper/cropper.min.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/profile_crop.js') !!}"></script>

<script src="{!! asset('assets/js/plugins/noUiSlider/nouislider.min.js') !!}"></script>

<link href="{!! asset('assets/js/plugins/noUiSlider/nouislider.min.css')!!}" rel="stylesheet">

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{!!csrf_token()!!}"
        }
    });

    var elem = document.querySelector('.js-switch');
    var switchery = new Switchery(elem, {color: '#1AB394'});
    
            var config = {
                '.chosen-select'           : {},
                '.chosen-select-deselect'  : {allow_single_deselect:true},
                '.chosen-select-no-single' : {disable_search_threshold:10},
                '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
                '.chosen-select-width'     : {width:"95%"}
                }
            for (var selector in config) {
                $(selector).chosen(config[selector]);
            }
</script>
@endsection