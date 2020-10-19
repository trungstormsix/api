@extends('layouts.admin')

@section('content')

<div id="home_categories" >
    <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">

        <br>
               <a href="{{URL::route('grammar.create_question') }}" type="button" class="btn btn-primary btn-lg">Add new Question</a>

    </div>
    <div class="col-lg-2">
        <br>
        <br>
        <form type="GET" action="{!! URL::route('grammar.search_questions') !!}">
            Search: <input name='search' value='{{ @$search }}' placeholder="Search" required />
        </form>
        <!--        <div class="pull-right tooltip-demo">
                    <a href="{{url('/admin/ielts/article/add')}}" class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new Article"><i class="fa fa-plus"></i> Add Article</a>
                </div>-->
    </div>
</div>
    
    <div class="ibox-content">
        <div class="table-responsive">
            
            <table class="table table-stripped  ">
                <thead>
                    <tr>
                        <th style="width: 40px">N.O</th>
                        <th >Id</th>
                        <th >Question</th>
                        <th >Answers</th>
                        <th >Correct</th>
                        <th >Type</th>
                        <th >Published</th>
                        <th >Explaination</th>
                        <th>Cats</th>
                        <th>Lessons</th>
                        <th >&nbsp;</th>
                    </tr>
                    </tr>
                </thead>
                <tbody>  
                    @php($t = "")
                    @foreach ($questions as $i => $question)
                    
                    <tr >	
                        <td style="color: #23c6c8">{{$i+1}}</td>
                        <td > {{$question->id}} </td>
                        <td> {{$question->question}}</td>
                        <td > {{$question->answers}} </td>
                        <td > {{$question->correct}} </td>
                        <td > {{$question->type}} </td>
                        <td> <input class="js-switch" style="display: none;" data-switchery="true" type="checkbox"
                                    data-id="{{ $question->id}}"  name="status{{ $question->id}}" {{ $question->published ? 'checked' : '' }} >
                        </td>
                        <td > {{ substr(  strip_tags($question->explanation),0,100) }}</td>
                        <td><div id="cat_container_{{$question->id}}" style="display: inline-block">
                                @foreach ($question->cat as $cat)
                                <span class="alert alert-warning delete-cat" style="display: inline-block;">
                                    <button aria-hidden="true" data-cat="{{$cat->id}}" data-main="{{$question->id}}" class="close" type="button">×</button>
                                    <a class="cat-link" target="_blank" href="{{ URL::route('grammar.lessons', $cat->id) }}">{{$cat->title}}</a> 
                                </span>
                                @endforeach
                            </div>
                            <input class="add_cat" data-id="{{$question->id}}" />
                            </div></td>
                        <td >
                            <div id='lesson_container_{{$question->id}}'>
                            @foreach ($question->article as $grammar)
                            <span class="alert alert-warning delete-lesson" style="display: inline-block;">
                                <button aria-hidden="true" data-gr="{{$grammar->id}}" data-main="{{$question->id}}" class="close" type="button">×</button>
                                <a class="lesson-link" target="_blank"  href="{{ URL::route('grammar.edit_lesson', $grammar->id) }}">{{$grammar->title}}</a> <br>
                            </span>
                            @endforeach
                            </div>
                            <input class="add_lesson" data-id="{{$question->id}}" />
                        </td>
                        <td style="width: 262px;"> 
                            <a href="{{ URL::route('grammar.edit_question', $question->id) }}" class="btn btn-info">Update</a>      
                            @if(!$question->published)
                            <a href="{{ URL::route('grammar.deleteQuestion', $question->id) }}" target="_blank" class="btn btn-danger">Delete</a>      
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="menu_pagination">
              {{@$questions->links()}}
            </div>
        </div>
    </div>
<br>
  <div class="row wrapper border-bottom white-bg page-footer">
      <br>
    <div class="col-lg-5">

 
    </div>
      <div class="col-lg-2">
        <a href="{{URL::route('grammar.create_question') }}" type="button" class="btn btn-success btn-sm dim"  target="_blank"><i class="fa fa-question-circle"></i> Add new Question</a>
    </div>
       @if(@$lesson_id)
    <form type="GET" action="{!! URL::route('grammar.crawlQuize') !!}">

    <div class="col-lg-4">
       
        <input name='lesson_id' type="hidden" class="form-control" value='{{$lesson_id}}' placeholder="Quizziz id" required />

        <input name='quiz_id' class="form-control" value='{{ @$search }}' placeholder="Quizziz id" required />
    </div>
    <div class="col-lg-1">
             <button class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Crawl Quiz"><i class="fa fa-check"></i> Crawl</button>
    </div>
    </form>
       @endif
      <br>
</div>
<br>
<br>
</div>

@endsection
@section("content_js")
<!--<script src="{!! asset('assets/js/plugins/dataTables/datatables.min.js') !!}"></script>-->
<link href="{!! asset('assets/css/plugins/dataTables/datatables.min.css')!!}" rel="stylesheet">
 
<style>
    tr.title{
        background:  #2f4050;
        font-weight: bold;
        color: #fff;
        padding-top: 10px;
    }
    tr.title td{

        padding-top: 20px !important;
    }
</style>
<script>


var elem = jQuery('.js-switch').each(function (index) {
    new Switchery(this, {color: '#1AB394'});

});


jQuery('.js-switch').change(function () {
    var question_id = jQuery(this).data('id');
    var published = jQuery(this).is(':checked') ? 1 : 0;
    var checked = jQuery(this).is(':checked');
    var that = this;
    if (question_id) {
        var that = this;
        jQuery.ajax({
            url: "{{ URL::route('PronQuestion.ajax_publish_question') }}",
            type: "GET",
            dataType: 'json',
            data: {question_id: question_id, published: published}
        }).done(function (data) {

        })
                .fail(function () {


                    alert("error");
                });
    }

});
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

<!--<script src="{!! asset('assets/js/jquery-ui-1.10.4.min.js') !!}"></script>-->

<link href="{!! asset('assets/css/plugins/summernote/summernote.css')!!}" rel="stylesheet">
<link href="{!! asset('assets/css/plugins/summernote/summernote-bs3.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/plugins/summernote/summernote.min.js') !!}"></script>
@endsection
