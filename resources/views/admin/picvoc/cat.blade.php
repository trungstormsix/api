@extends('layouts.admin')

@section('content')
<!-- header -->
<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/picvoc/cat') }}">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{empty($title) ?  'oCoder' : $title}}</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>  
                <li>
                    <a href="{{URL::route('picvoc.cats')}}">Picvoc Cats</a>
                </li>

                <li class="active">
                    <strong>{{$cat ? "Edit" : "Create New"}} Cat</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <br>
            <br>
            <div class="pull-right tooltip-demo">
                <button   class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new playlist"><i class="fa fa-plus"></i> Save</button>
                <a href="{{url('/admin/picvoc/cats')}}" class="btn btn-danger btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cancel Edit"><i class="fa fa-times"></i> Discard</a>
            </div>
        </div>
    </div>

    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{empty($cat) ? old('id') : $cat->id}}" />
    <div class="row picvoc_cat">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">                
                <div class="ibox-content">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Title
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='title' value="{{old('title') ? old('title') : ($cat ? $cat->title :'') }}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>     
                    

                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Published
                        </label>
                        <div class="col-sm-10">

                            <input class="js-switch" value="1" style="display: none;" data-switchery="true" type="checkbox" name="status" {{($cat == false || ($cat && $cat->status)) ? 'checked' : '' }} >

                        </div>
                    </div>
                     
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-2 control-label">
                        <label class="control-label">   
                            Image
                            
                        </label>
                            @if($img_data)
                            <br>
                            Size: {{$img_data[0]}}/{{$img_data[1]}}<br>  Type: {{$img_data['mime']}}<br>
                                @endif
                        </div>
                        <div class="col-sm-10">
                             <div class="img-wrapper avatar-view" title="Change the Cat image">
                            @if($cat && $cat->img)
                           
                                
                                <img id="avt_image" style="max-width: 99%" src="{{url('/')}}/../api/image/{{ $cat->img ? $cat->img : '/images/avatar1.jpg'}}" />  
                                <i class="fa fa-camera" data-toggle="modal" data-target="#profilePictureModal"></i> 
                           
                            <br>
                            @else
                                                            <i class="fa fa-camera" data-toggle="modal" data-target="#profilePictureModal"></i> 

                            @endif
                            </div>
                            <input class="form-control" type="text" name='img' value="{{$cat ? $cat->img : ""}}">                          
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Image Link
                        </label>
                        <div class="col-sm-10">
                            
                            <input class="form-control" type="text" name='img_link' value="" placeholder="paste the link to save image as cat image">

                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Parent
                        </label>
                        <div class="col-sm-10">
                            
                            <select name="parent_id" class="form-control chosen-select" >
                                <option value="0">none</option>       
                                @foreach($parents as $parent)
                                <option value="{{$parent->id}}" {{$cat && $cat->parent_id == $parent->id ? "selected='selected'" : ""}}>{{$parent->title}}</option>
 

                                @endforeach
                            </select>
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
        <form class="avatar-form" action="{!! URL::route('picvoc.catImg') !!}" enctype="multipart/form-data" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{@$cat->id}}" />
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Profile Picture</h4>
                </div>
                <div class="modal-body">
                    @php($img = @$cat->img ? $cat->img : "")
                    <!-- Upload image and data -->
                    <div class="avatar-upload">
                        <input type="hidden" class="avatar-src" name="avatar_src" value="{{$img ? $img : '/images/avatar1.jpg'}}">
                        <input type="hidden" class="avatar-data" name="avatar_data">

                    </div>
                    <div class="img-center">
                        <div class="img-wrapper avatar-wrapper">
                            <img  src="{{url('/')}}/../api/image/{{$img ? $img : '/images/avatar1.jpg'}}" />
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
<script src="{!! asset('assets/js/picvoc_cat_crop.js') !!}"></script>

<script src="{!! asset('assets/js/plugins/noUiSlider/nouislider.min.js') !!}"></script>

<link href="{!! asset('assets/js/plugins/noUiSlider/nouislider.min.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/plugins/chosen/chosen.jquery.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/chosen/chosen.css')!!}" rel="stylesheet">
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