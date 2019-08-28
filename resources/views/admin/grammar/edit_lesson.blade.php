@extends('layouts.admin')

@section('content')
 <form class="form-horizontal" role="form" method="POST" action="{!! URL::route('grammar.save_lesson')!!}">
 <input  type="hidden" name='id' value="{{ $lesson ? $lesson->id : '' }}">
 <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{ $lesson ? "Edit" : 'Create' }} Grammar Lesson</h2>


            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin')}}">Home</a>
                </li>
                <li>
                    <a href="{{url('/admin/grammar')}}">Grammar</a>
                </li>
                <li class="active">
                    <strong>{{ $lesson ? "Edit" : 'Create' }} Lesson</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <br>
            <br>
            <div class="pull-right tooltip-demo">
                @if( $lesson)
                        <a href="{!! URL::route('grammar.create_lesson')!!}" type="button" class="btn btn-sm btn-info  dim"><i class="fa fa-plus"></i> New</a>
                @endif       
                <button  class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new Articles"><i class="fa fa-check"></i> Save</button>
             </div>
        </div>
    </div>


    {{ csrf_field() }}
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">                
                <div class="ibox-content">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Title
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='title' value="{{old('title') ? old('title') : ($lesson? $lesson->title : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>                  
                     
                     
                     <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Thumbnail
                        </label>
                        <div class="col-sm-10">
                            @php ($value = (old('intro_img') ? old('intro_img') : ($lesson ? $lesson->intro_img : '')))
                            {!! App\library\OcoderHelper::GenerateIcon($value, 'thumbnail', URL::asset("/public/filemanager/index.html"), 'intro_img') !!}
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>   

<!--                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Video
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='video' value="{{old('video') ? old('video') : ($lesson? $lesson->video : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>-->
                    
                    
                      <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Content
                        </label>
                        <div class="col-sm-10">
                            <textarea id="enEditor" class="form-control" type="text" name='content' >{!! old('content') ? old('content') : ($lesson ? $lesson->content : '') !!}</textarea>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>   
                    
               <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Category
                        </label>
                        <div class="col-sm-10">
                            @php ($cat_ids = old('cat_ids') ? old('cat_ids') : ($cat_ids ? $cat_ids : []))
                            <select multiple  name="cat_ids[]" data-placeholder="Choose a Cat..." class="chosen-select" style="width:350px;" tabindex="2">
                                <option value="0">none</option>	
                                @foreach ($categories as $category)	
                                  		
                                <option value="{{$category->id}}" {{in_array($category->id, $cat_ids) ? "selected='selected'" : ""}}>{{$category->title}}</option>                               
                                		
                                @endforeach
                            </select>
                            <br>
                             
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>  
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Published
                        </label>
                        <div class="col-sm-10">

                            <input class="js-switch" value="1" style="display: none;" data-switchery="true" type="checkbox" name="published" {{(old('published') || ($lesson && $lesson->published)) ? 'checked' : '' }} >

                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>  
                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Level
                        </label>
                         @php($type = old('order') ? old('order') : ($lesson ? $lesson->order : '0'))
                        <div class="col-sm-10">
                            <select name="order">
                                <option value="1" {{$type == 0 ? "selected='selected'" : ""}}>None</option>
                                <option value="1" {{$type == 1 ? "selected='selected'" : ""}}>Intro</option>
                                <option value="2" {{$type == 2 ? "selected='selected'" : ""}}>Basic</option>
                                <option value="3" {{$type == 3 ? "selected='selected'" : ""}}>Intermidiate</option>
                                <option value="3" {{$type == 4 ? "selected='selected'" : ""}}>Advance</option>
                                <option value="3" {{$type == 5 ? "selected='selected'" : ""}}>Supper Advance</option>
                             </select>            
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div> 
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                           Link
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='link' value="{{old('link') ? old('link') : ($lesson? $lesson->link : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    
                </div>
                 <button class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add new playlist" style="position: fixed; bottom: 30px;right: 42px;">
                            <i class="fa fa-check"></i> Save</button>
            </div>
        </div>
    </div>
 </form>
@endsection
@section("content_js")
<script src="http://ocodereducation.com/template/js/ckeditor/ckeditor.js"></script>
<!--<script src="{!! asset('assets/ckeditor/ckeditor.js') !!}"></script>-->

<script src="{!! asset('assets/js/plugins/sweetalert/sweetalert.min.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/sweetalert/sweetalert.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/plugins/chosen/chosen.jquery.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/chosen/chosen.css')!!}" rel="stylesheet">

<script>
   CKEDITOR.replace('enEditor', {
        filebrowserBrowseUrl: '{{URL::asset("filemanager")}}/index.html',
         
        enterMode : CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
          init: function( editor ) {
        
        editor.addContentsCss( 'http://ocodereducation.com/template/css/grammar.css' );
    }
    
    });
    CKEDITOR.config.contentsCss = 'http://ocodereducation.com/template/css/grammar.css' ;     
    CKEDITOR.config.height = 600;   
    
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
