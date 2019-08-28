@extends('layouts.admin')

@section('content')
<!-- header -->
<form class="form-horizontal" role="form" method="POST" action="">
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
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">                
                    <div class="ibox-content">
                        <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                Quizlet
                            </label>
                            <div class="col-sm-10">
                                <textarea name="quizlet_html" style="width: 100%" cols="70" rows="10">{!! old('cat_id') ? old('cat_id') : "" !!}</textarea>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">   
                                Category
                            </label>
                            <div class="col-sm-10">
                                @php ($category_id = old('cat_id') ? old('cat_id') : 12)
                                <select name="cat_id" data-placeholder="Choose a Category..." class="chosen-select" style="width:350px;" tabindex="2">
                                    <option value="0">none</option>	
                                    @foreach ($categories_level as $categories_level_1)	
                                    @if ($categories_level_1->id == $category_id)		
                                    <option value="{{$categories_level_1->id}}" selected="selected">{{$categories_level_1->title}}</option>
                                    @else		
                                    <option value="{{$categories_level_1->id}}">{{$categories_level_1->title}}</option>
                                    @endif
                                    @foreach ($categories as $categories_level_2)
                                    @if ($categories_level_2->parent_id == $categories_level_1->id)
                                    @if ($categories_level_2->id == $category_id)	
                                    <option value="{{$categories_level_2->id}}" selected="selected">&nbsp;&nbsp;&nbsp; {{$categories_level_2->title}}</option>
                                    @else		
                                    <option value="{{$categories_level_2->id}}">&nbsp;&nbsp;&nbsp; {{$categories_level_2->title}}</option>
                                    @endif
                                    @foreach ($categories as $categories_level_3)
                                    @if ($categories_level_3->parent_id == $categories_level_2->id)
                                    @if ($categories_level_3->id == $category_id)		
                                    <option value="{{$categories_level_3->id}}" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$categories_level_3->title}}</option>
                                    @else		
                                    <option value="{{$categories_level_3->id}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$categories_level_3->title}}</option>
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
                                Type
                            </label>
                            <div class="col-sm-10">
                                <span id="quizlet" class="btn btn-primary">Quizlet</span>
                                <span id="vocabulary"  class="btn btn-primary">Vocabulary.com</span>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div
                        <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                Item Selector
                            </label>
                            <div class="col-sm-10">
                                <input class="item_selector" name="item_selector" value="{!! old('item_selector') ? old('item_selector') : '' !!}" placeholder=".SetPageTerms-term"/>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                Word Selector
                            </label>
                            <div class="col-sm-10">
                                <input class="word_selector" name="word_selector" value="{!! old('word_selector') ? old('word_selector') : '' !!}" placeholder=".SetPageTerm-wordText"/>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                Mean Selector
                            </label>
                            <div class="col-sm-10">
                                <input class="mean_selector" name="mean_selector" value="{!! old('mean_selector') ? old('mean_selector') : '' !!}" placeholder=".SetPageTerm-definitionText"/>
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
@endsection


@section('content_js')
<script src="{!! asset('assets/ckeditor/ckeditor.js') !!}"></script>
<script src="{!! asset('assets/js/plugins/chosen/chosen.jquery.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/chosen/chosen.css')!!}" rel="stylesheet">

<script>

CKEDITOR.replace('related', {
filebrowserBrowseUrl: '{{URL::asset("filemanager")}}/index.html',
});
$('#show_hide_q').click(function(e){e.preventDefault(); $('.questions').toggle(300); })
        var elem = document.querySelector('.js-switch');
var switchery = new Switchery(elem, {color: '#1AB394'});
var linkRemoveCat = "{{url('admin/picvoc/delete-cat')}}";
var linkAutocompleteCat = "{{url('admin/picvoc/search-cat')}}";
var linkAddCat = "{{url('admin/picvoc/add-cat')}}";
var linkAutocompleteGrammar = "";</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link href="{!! asset('assets/css/plugins/summernote/summernote.css')!!}" rel="stylesheet">
<link href="{!! asset('assets/css/plugins/summernote/summernote-bs3.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/plugins/summernote/summernote.min.js') !!}"></script>
<script>
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
    $(".item_selector").val(".SetPageTerms-term");
    $(".word_selector").val(".SetPageTerm-wordText");
    $(".mean_selector").val(".SetPageTerm-definitionText");
    $("#quizlet").click(function(){
        $(".item_selector").val(".SetPageTerms-term");
        $(".word_selector").val(".SetPageTerm-wordText");
        $(".mean_selector").val(".SetPageTerm-definitionText");
    });
    $("#vocabulary").click(function(){
        $(".item_selector").val(".learnable");
        $(".word_selector").val(".dynamictext");
        $(".mean_selector").val(".definition");
    });
</script>
@endsection