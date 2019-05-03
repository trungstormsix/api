@extends('layouts.admin')

@section('content')
 <form class="form-horizontal" role="form" method="POST" action="{{ URL::route('grammar.save_question') }}">
 <input  type="hidden" name='id' value="{{ $question ? $question->id : '' }}">
 <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{ $question ? "Edit" : 'Create' }} Question</h2>


            
        </div>
        <div class="col-lg-2">
            <br>
            <br>
            <div class="pull-right tooltip-demo">
                
                @if( $question)
                        <a href="{{ URL::route('grammar.create_question') }}" type="button" class="btn btn-sm btn-info  dim"><i class="fa fa-plus"></i> New</a>
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
                            Question
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='question' value="{{old('question') ? old('question') : ($question ? $question->question : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Correct
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='correct' value="{{old('correct') ? old('correct') : ($question ? $question->correct : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Answers
                        </label>
                        <div class="col-sm-10">
                           
                            @php($answers = $question ? @json_decode($question->answers) : (old('answers') ? old('answers') : [] )) 
                            @if($answers)
                             
                                @foreach($answers as $an)              
                                <input name="answers[]" value="{{$an}}" style="width: 100%;" spellcheck="true"/>
                                @endforeach
                            @else
                           
                            <input name="answers[]" value="" style="width: 100%;" spellcheck="true"/>
                            <input name="answers[]" value="" style="width: 100%;" spellcheck="true"/>
                            <input name="answers[]" value="" style="width: 100%;" spellcheck="true"/>
                            <input name="answers[]" value="" style="width: 100%;" spellcheck="true"/>

                            @endif
                            <input name="answers[]" value="" style="width: 100%;" spellcheck="true"/>                    
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Type
                        </label>
                         @php($type = old('type') ? old('type') : ($question ? $question->type : '1'))
                        <div class="col-sm-10">
                            <select name="type">
                                <option value="1" {{$type == 1 ? "selected='selected'" : ""}}>Text</option>
                                <option value="2" {{$type == 2 ? "selected='selected'" : ""}}>Audio</option>
                                <option value="3" {{$type == 3 ? "selected='selected'" : ""}}>Image</option>
                             </select>            
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>   
                     <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Level
                        </label>
                         @php($type = old('level') ? old('level') : ($question ? $question->level : '1'))
                        <div class="col-sm-10">
                            <select name="type">
                                <option value="1" {{$type == 1 ? "selected='selected'" : ""}}>Easy</option>
                                <option value="2" {{$type == 2 ? "selected='selected'" : ""}}>Intermidiate</option>
                                <option value="3" {{$type == 3 ? "selected='selected'" : ""}}>Advance</option>
                             </select>            
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>   
                    @if($question)
                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Categories
                        </label>
                        <div class="col-sm-10">
                           <div id="cat_container_{{$question->id}}" style="display: inline-block">
                                @foreach ($question->cat as $cat)
                                <span class="alert alert-warning delete-cat" style="display: inline-block;">
                                    <button aria-hidden="true" data-cat="{{$cat->id}}" data-main="{{$question->id}}" class="close" type="button">×</button>
                                    <a class="cat-link" target="_blank" href="{{ URL::route('grammar.lessons', $cat->id) }}">{{$cat->title}}</a> 
                                </span>
                                @endforeach
                            </div>
                            <input placeholder="type a grammar ctegory" class="add_cat" data-id="{{$question->id}}" />
                            </div> 
                             
                      </div>
                    <div class="hr-line-dashed"></div>  
                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Lessons
                        </label>
                        <div class="col-sm-10">
                           <div id='lesson_container_{{$question->id}}'>
                            @foreach ($question->article as $grammar)
                            <span class="alert alert-warning delete-lesson" style="display: inline-block;">
                                <button aria-hidden="true" data-gr="{{$grammar->id}}" data-main="{{$question->id}}" class="close" type="button">×</button>
                                <a class="lesson-link" target="_blank"  href="{{ URL::route('grammar.edit_lesson', $grammar->id) }}">{{$grammar->title}}</a> <br>
                            </span>
                            @endforeach
                            </div>
                            <input placeholder="type a grammar lesson" class="add_lesson" data-id="{{$question->id}}" />
                             
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>  
                    @endif
                     
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Published
                        </label>
                        <div class="col-sm-10">

                            <input class="js-switch" value="1" style="display: none;" data-switchery="true" type="checkbox" name="published" {{(old('published') || ( $question && $question->published)) ? 'checked' : '' }} >

                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>  
                    
                     <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Explaination
                        </label>
                        <div class="col-sm-10">
                                <textarea id="editExplaination" class="form-control" type="text" name='explanation' >{!! old('explanation') ? old('explanation') : ($question ? $question->explanation : '') !!}</textarea>

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
<script src="{!! asset('assets/ckeditor/ckeditor.js') !!}"></script>

<script src="{!! asset('assets/js/plugins/sweetalert/sweetalert.min.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/sweetalert/sweetalert.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/plugins/chosen/chosen.jquery.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/chosen/chosen.css')!!}" rel="stylesheet">

<script>
   CKEDITOR.replace('editExplaination', {
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
            
            
function ajaxAddCat(cat_id, question_id, cat_title) {
    if (cat_id) {
        var that = this;
        jQuery.ajax({
            url: "{{ URL::route('grammar.add_question_cat') }}",
            type: "POST",
            dataType: 'json',
            data: {"_token": "{{ csrf_token() }}",cat_id: cat_id, question_id: question_id}
        }).done(function (data) {
            if(data.changed.attached.length > 0)
            jQuery('#cat_container_' + question_id).append('<span class="alert alert-warning delete-cat" style="display: inline-block;">' 
                                +    '<button aria-hidden="true" data-cat="' + cat_id + '" data-main="' + question_id + '" class="close" type="button">×</button>'
                                +    '<a class="cat-link" target="_blank" href="' + data.url + '">' + cat_title + '</a> '
                                + '</span>');
        })
        .fail(function () {
            alert("error");
        });
    }
}

 function ajaxAddLesson(lesson_id, question_id, lesson_title) {
    if (lesson_id) {
        var that = this;
        jQuery.ajax({
            url: "{{ URL::route('grammar.add_question_lesson') }}",
            type: "POST",
            dataType: 'json',
            data: {"_token": "{{ csrf_token() }}",lesson_id: lesson_id, question_id: question_id}
        }).done(function (data) {
            if(data.changed.attached.length > 0)
            jQuery('#lesson_container_' + question_id).append('<span class="alert alert-warning delete-lesson" style="display: inline-block;">' 
                               + '<button aria-hidden="true" data-gr="' + lesson_id + '" data-main="' + question_id + '" class="close" type="button">×</button>'
                               + '<a class="lesson-link" target="_blank" href="' + data.url + '">' + lesson_title + '</a> <br>'
                            +'</span>');
        })
        .fail(function (error) {
            alert(error.error.message);
        });
    }
}
jQuery(document).ready(function () {
    jQuery(".add_lesson").autocomplete({
        source: "{{url('admin/listening/autocomplete-grammar')}}",
        minLength: 2,
        select: function (event, ui) {
            event.preventDefault();
            var dl_id = jQuery(event.target).data('id');
            ajaxAddLesson(ui.item.key, dl_id, ui.item.value);
            this.value = "";
        },
    });
    jQuery(".add_cat").autocomplete({
        source: "{{ URL::route('grammar.ajax_get_cats') }}",
        select: function (event, ui) {
            event.preventDefault();
            var dl_id = jQuery(event.target).data('id');
            ajaxAddCat(ui.item.key, dl_id, ui.item.value);
            this.value = "";
        },
    });
});

jQuery('.delete-lesson button').click(function () {
    var gr_id = jQuery(this).data('gr');
    var main_id = jQuery(this).data('main');
    if (gr_id) {
        var that = this;
        jQuery.ajax({
            url: "{{ URL::route('grammar.delete_lesson_question') }}",
            type: "GET",
            dataType: 'json',
            data: {lesson_id: gr_id, question_id: main_id}
        }).done(function (data) {
            jQuery(that).parent().remove();
        })
                .fail(function () {
                    alert("error");
                });
    }
});
jQuery('.delete-cat button').click(function () {
    var cat_id = jQuery(this).data('cat');
    var main_id = jQuery(this).data('main');
    if (cat_id) {
        var that = this;
        jQuery.ajax({
            url: "{{ URL::route('grammar.delete_cat_question') }}",
            type: "GET",
            dataType: 'json',
            data: {cat_id: cat_id, question_id: main_id}
        }).done(function (data) {
            jQuery(that).parent().remove();
        })
                .fail(function () {
                    alert("error");
                });
    }
})
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
@endsection
