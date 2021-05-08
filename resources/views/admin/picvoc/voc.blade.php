@extends('layouts.admin')

@section('content')
<!-- header -->
<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/picvoc/voc/save') }}">
    {{ csrf_field() }}
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{empty($title) ?  'oCoder' : $title}}</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin')}}">Home</a>
                </li>
                <li>
                    <a href="{{url('admin/cats')}}">Cat</a>
                </li>                
                <li class="active">
                    <strong>{{$voc->en_us}}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <br>
            <br>
            <div class="pull-right tooltip-demo">
                <button class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add new playlist"><i class="fa fa-plus"></i> Save</button>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeIn">
        <div class="row picvoc_cat">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">                
                    <div class="ibox-content">
                        <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                Id
                            </label>
                            <div class="col-sm-10">
                                <input type="hidden" name="id" value="{{$voc->id}}" />
                                {{$voc->id}}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                Word
                            </label>
                            <div class="col-sm-10"><input name="en_us" value="{{$voc->en_us}}" /></div>     
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                Type
                            </label>
                            <div class="col-sm-10"><input name="en_us_type" value="{{$voc->en_us_type}}" /></div>     
                        </div>
                        <div class="hr-line-dashed"></div>
                        
                        
                         <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                US Pron
                            </label>
                            <div class="col-sm-10"><input name="en_us_pr" value="{{$voc->en_us_pr}}" />  
                                <audio  id="audio_us_{{ $voc->id}}">
                                        <source src="{{url('/')}}/../api/audio/picvoc/{{$voc->en_us_audio}}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                <button  class="btn btn-sm btn-primary"  onclick="playAudio('audio_us_{{ $voc->id}}')" type="button">Play</button><br>
                               <input class="form-control" type="text" name="en_us_mp3_link" value="" placeholder="paste the link to save us pronunciation mp3 file">
                             </div>     
                        </div>
                        <div class="hr-line-dashed"></div>
                          <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                UK Pron
                            </label>
                            <div class="col-sm-10"><input name="en_uk_pr" value="{{$voc->en_uk_pr}}" />  
                                <audio  id="audio_uk_{{ $voc->id}}">
                                        <source src="{{url('/')}}/../api/audio/picvoc/{{$voc->en_uk_audio}}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                <button  class="btn btn-sm btn-primary"  onclick="playAudio('audio_uk_{{ $voc->id}}')" type="button">Play</button><br>
                               <input class="form-control" type="text" name="en_uk_mp3_link" value="" placeholder="paste the link to save us pronunciation mp3 file">
                             </div>     
                        </div>
                        <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-2 control-label">
                        <label class="control-label">   
                            Image
                            
                        </label>
                            @if(@$img_data)
                            <br>
                            Size: {{$img_data[0]}}/{{$img_data[1]}}<br>  Type: {{$img_data['mime']}}<br>
                                @endif
                        </div>
                        <div class="col-sm-10">
                             <div class="img-wrapper avatar-view" title="Change the Vocabulary image">
                            @if($voc && $voc->image)
                           
                                
                                <img id="avt_image" style="max-width: 99%" src="{{url('/')}}/../api/image/picvoc/{{ $voc->image ? $voc->image : '/images/avatar1.jpg'}}" />  
                                <i class="fa fa-camera" data-toggle="modal" data-target="#profilePictureModal"></i> 
                           
                            <br>
                            @else
                                                            <i class="fa fa-camera" data-toggle="modal" data-target="#profilePictureModal"></i> 

                            @endif
                            </div>
                            <input class="form-control" type="text" name='img' value="{{$voc ? $voc->image : ""}}">                          
                        </div>
                    </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Cats
                            </label>
                            <div class="col-sm-10">
                                                            @php ($cat_ids = old('cat_ids') ? old('cat_ids') : ($cat_ids ? $cat_ids : []))

                                <select name="cat_ids[]" multiple  data-placeholder="Choose Cats..." class="chosen-select" style="width:350px;" tabindex="2">
                                       @foreach($cats as $cat)
                                            <option value="{{$cat->id}}" {{in_array($cat->id, $cat_ids) ? "selected='selected'" : ""}}>{{$cat->title}}</option>

                                       @endforeach
                                </select>
                                <div id="cat_container" style="display: inline-block">
                                    @foreach ($voc->cats as $cat)
                                    <span class="alert alert-warning remove-cat" style="display: inline-block;">
                                        <button aria-hidden="true" data-cat="{{$cat->id}}" data-main="{{$voc->id}}" class="close" type="button">×</button>
                                        <a class="cat-link" href="{{url('admin/picvoc/vocabularies/'.$cat->id)}}">{{$cat->title}}</a> 
                                    </span>
                                    @endforeach
                                </div>
                                <!--<input id="add_cat" data-id="{{$voc->id}}" />-->
                            </div>
                        </div>      
 
                         

                         
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Means
                            </label>
                            <div class="col-sm-10">
                                <div class="ibox float-e-margins">                                     
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>N.o</th>                                
                                                <th>Id</th>      
                                                <th>Lang</th>
                                                <th>Mean</th>
                                                <th>rate</th>
                                                <th>dis_like</th>
                                                <th>updated</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                            @php($i = 0)
                                            @foreach($means as $mean)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{$mean->id}}</td>
                                                <td>{{$mean->lang}}</td>
                                                <td>{{$mean->mean}}</td>
                                                <td>{{$mean->rate}}</td>
                                                <td>{{$mean->dis_like}}</td>
                                                <td>{{$mean->updated}}</td>
                                            </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                Mean
                            </label>
                            <div class="col-sm-10"><textarea name="en_us_mean" class="form-control">{{$voc->en_us_mean}}</textarea></div>     
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                Examples
                            </label>
                            <div class="col-sm-10"><textarea name="en_us_ex" class="form-control">{{$voc->en_us_ex}}</textarea></div>     
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Status
                            </label>
                            <div class="col-sm-10">
                                <input class="js-switch" style="display: none;" data-switchery="true" type="checkbox" name="status" {{(old('status') || $voc->status) ? 'checked' : '' }} >
                            </div>
                        </div>         
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                updated
                            </label>
                            <div class="col-sm-10">
                                {{$voc->updated}}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                         

                        
                         
                        <button class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add new playlist"
                                style="position: fixed; bottom: 40px;right: 42px;"    >
                            <i class="fa fa-plus"></i> Save</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>>

<div class="modal inmodal" id="profilePictureModal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <form class="avatar-form" action="{!! URL::route('picvoc.vocImg') !!}" enctype="multipart/form-data" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{@$voc->id}}" />
            <div class="modal-content animated bounceInRight">
<!--                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Profile Picture</h4>
                   
                </div>-->
                <div class="modal-body">
                    @php($img = @$voc->image ? $voc->image : "")
                    <!-- Upload image and data -->
                    <div class="avatar-upload">
                        <input type="hidden" class="avatar-src" name="avatar_src" value="{{$img ? $img : '/images/avatar1.jpg'}}">
                        <input type="hidden" class="avatar-data" name="avatar_data">

                    </div>
                    <div class="img-center">
                        <div class="img-wrapper avatar-wrapper">
                            <img  src="{{url('/')}}/../api/image/picvoc/{{$img ? $img : '/images/avatar1.jpg'}}" />
                        </div>

                    </div>
<!--                    <div class="row zoom-row">
                        <div class="col-sm-1 small">
                            <i class="fa fa-image small"></i>
                        </div>
                        <div class="col-sm-10">
                            <div id="range_slider"></div>
                        </div>
                        <div class="col-sm-1">
                            <i class="fa fa-image big"></i>
                        </div>
                    </div>-->
                    
                    <div class="row"> <div class="col-sm-6">Width: <span id="crop_img_width"></span> Height: <span id="crop_img_height"></span></div>
                    <div class="col-sm-6" id="toggle-aspect-ratio">
                        <span class="btn" data-value="NaN">Freeform</span>
                        <span class="btn" data-value="1.77777">16/9</span>
                        <span class="btn" data-value="1.33333">4/3</span>
                    </div>
                    </div>
                                         <div id="preview"></div>

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
 

@section('content_js')
 <script src="{!! asset('assets/js/plugins/cropper/cropper.min.js') !!}"></script>
<link href="{!! asset('assets/js/plugins/cropper/cropper.min.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/picvoc_voc_crop.js') !!}"></script>
<script src="{!! asset('assets/js/plugins/noUiSlider/nouislider.min.js') !!}"></script>
<link href="{!! asset('assets/js/plugins/noUiSlider/nouislider.min.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/plugins/chosen/chosen.jquery.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/chosen/chosen.css')!!}" rel="stylesheet">

<script>
//    CKEDITOR.replace('related', {
//        filebrowserBrowseUrl: '{{URL::asset("filemanager")}}/index.html',
//    });
    $('#show_hide_q').click(function(e){e.preventDefault(); $('.questions').toggle(300); })
            var elem = document.querySelector('.js-switch');
    var switchery = new Switchery(elem, {color: '#1AB394'});
    
    var linkRemoveCat = "{{url('admin/picvoc/delete-cat')}}";
    var linkAutocompleteCat = "{{url('admin/picvoc/search-cat')}}";
    var linkAddCat = "{{url('admin/picvoc/add-cat')}}";
    var linkAutocompleteGrammar = "";
    //click to play audio
    function playAudio(id) { 
        var x = document.getElementById(id); 
      x.play(); 
    } 
    
     
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

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link href="{!! asset('assets/css/plugins/summernote/summernote.css')!!}" rel="stylesheet">
<link href="{!! asset('assets/css/plugins/summernote/summernote-bs3.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/plugins/summernote/summernote.min.js') !!}"></script>
<style>
    .cropper-modal {
    opacity: 0.7;
    background-color: #000;
}
  </style>
@endsection