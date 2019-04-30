@extends('layouts.admin')

@section('content')
 <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/pronunciation/save_cat') }}">
 <input  type="hidden" name='id' value="{{ $cat ? $cat->id : '' }}">
 <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{ $cat ? "Edit" : 'Create' }} Pronunciation Category</h2>


            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin')}}">Home</a>
                </li>
                <li>
                    <a href="{{url('/admin/pronunciation')}}">Pronunciation</a>
                </li>
                <li class="active">
                    <strong>{{ $cat ? "Edit" : 'Create' }} Cat</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <br>
            <br>
            <div class="pull-right tooltip-demo">
                @if( $cat)
                        <a href="{{ url('admin/pronunciation/create_cat')}}" type="button" class="btn btn-sm btn-primary dim">New</a>
                @endif       
                <button  class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new Articles"><i class="fa fa-plus"></i> Save</button>
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
                            <input class="form-control" type="text" name='title' value="{{old('title') ? old('title') : ($cat? $cat->title : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Title VI
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='title_vi' value="{{old('title_vi') ? old('title_vi') : ($cat? $cat->title_vi : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Main Title (Parent Title)
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='main_title' value="{{old('main_title') ? old('main_title') : ($cat? $cat->main_title : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Thumbnail
                        </label>
                        <div class="col-sm-10">
                            @php ($value = (old('thumbnail') ? old('thumbnail') : ($cat ? $cat->thumbnail : '')))
                            {!! App\library\OcoderHelper::GenerateIcon($value, 'thumbnail', URL::asset("/public/filemanager/index.html"), 'thumbnail') !!}
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>   

                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Video
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='video' value="{{old('video') ? old('video') : ($cat? $cat->video : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            PCAT
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='pcat' value="{{old('pcat') ? old('pcat') : ($cat? $cat->pcat : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    
                      <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Content
                        </label>
                        <div class="col-sm-10">
                            <textarea id="enEditor" class="form-control" type="text" name='en' >{!! old('en') ? old('en') : ($cat ? $cat->en : '') !!}</textarea>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>   
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Order
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='IC' value="{{old('IC') ? old('IC') : ($cat? $cat->IC : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Published
                        </label>
                        <div class="col-sm-10">

                            <input class="js-switch" value="1" style="display: none;" data-switchery="true" type="checkbox" name="published" {{(old('published') || ($cat && $cat->published)) ? 'checked' : '' }} >

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
<script src="{!! asset('assets/ckeditor/ckeditor.js') !!}"></script>

<script src="{!! asset('assets/js/plugins/sweetalert/sweetalert.min.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/sweetalert/sweetalert.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/plugins/chosen/chosen.jquery.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/chosen/chosen.css')!!}" rel="stylesheet">

<script>
   CKEDITOR.replace('enEditor', {
        filebrowserBrowseUrl: '{{URL::asset("filemanager")}}/index.html',
         
        enterMode : CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P
    
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
