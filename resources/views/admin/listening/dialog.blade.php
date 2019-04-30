@extends('layouts.admin')

@section('content')
<!-- header -->
<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/listening/dialog/save') }}">
    {{ csrf_field() }}
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{empty($title) ?  'oCoder' : $title}}</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li>
                    <a href="{{url('admin/listening')}}">Listening</a>
                </li>
                <li class="active">
                    <strong>{{$dialog->title}}</strong>
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
                                Id
                            </label>
                            <div class="col-sm-10">
                                <input type="hidden" name="id" value="{{$dialog->id}}" />
                                {{$dialog->id}}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                Title
                            </label>
                            <div class="col-sm-10"><input name="title" value="{{$dialog->title}} " /></div>     
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                audio
                            </label>
                            <div class="col-sm-10">
                                {!!$dialog->audio!!}<br><br>
                                <audio controls="">
                                    <source src="http://ocodereducation.com/api/audio/{!!$dialog->audio!!}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        </div>       
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Cats
                            </label>
                            <div class="col-sm-10">
                                <div id="cat_container" style="display: inline-block">
                                    @foreach ($dialog->cats as $cat)
                                    <span class="alert alert-warning remove-cat" style="display: inline-block;">
                                        <button aria-hidden="true" data-cat="{{$cat->id}}" data-main="{{$dialog->id}}" class="close" type="button">×</button>
                                        <a class="cat-link" href="{{url('admin/listening/cat/'.$cat->id)}}">{{$cat->title}}</a> 
                                    </span>
                                    @endforeach
                                </div>
                                <input id="add_cat" data-id="{{$dialog->id}}" />
                            </div>
                        </div>      
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Grammar
                            </label>
                            <div class="col-sm-3">      
                                Grammar Lession: 
                                <input id="add_grammar_id"  type="hidden" />
                                <input id="add_grammar" data-id="{{$dialog->id}}" /><br><br>
                                Sentence: 
                                <input id="add_grammar_sentence"  />
                                <button id="add_grammar_button" data-id="{{$dialog->id}}" class="btn btn-primary btn-xs m-l-sm" type="button">Add</button>
                            </div>
                            <div class="col-sm-7" id="grammar_container">

                                @foreach ($dialog->grammars as $grammar)
                                <span class="alert alert-warning remove-grammar" style="display: inline-block;">
                                    <button aria-hidden="true" data-gr="{{$grammar->id}}" data-main="{{$dialog->id}}" class="close" type="button">×</button>
                                    <a class="cat-link" href="{{url('admin/grammar/lesson/'.$grammar->id)}}">{{$grammar->title}}</a> <br>
                                    <span>{{$grammar->pivot->ex}}</span>
                                </span>
                                @endforeach
                            </div>

                        </div>  
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Dialog
                            </label>
                            <div class="col-sm-10">
                                <div class="ibox float-e-margins">
                                    <button id="edit" class="btn btn-primary btn-xs m-l-sm"   type="button">Edit</button>
                                    <button id="save" class="btn btn-primary  btn-xs"   type="button">Done</button>
                                    <textarea id="dialog_content" name="dialog" spellcheck="true" style="display: none;"> {!!$dialog->dialog!!}</textarea>
                                    <div class="ibox-content no-padding" spellcheck="true">
                                        <div class="click2edit wrapper p-md">
                                            {!!$dialog->dialog!!}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Vocabularies
                            </label>
                            <div class="col-sm-10">
                                <div class="ibox float-e-margins">
                                    <button id="edit_voc" class="btn btn-primary btn-xs m-l-sm"   type="button">Edit</button>
                                    <button id="save_voc" class="btn btn-primary  btn-xs"   type="button">Done</button>
                                    <!--<textarea id="voc_content" name="vocabulary" style="display: none;"> {!!$dialog->vocabulary!!}</textarea>-->
                                    <!--                                    <div class="ibox-content no-padding">
                                                                            <div class="click2editvoc wrapper p-md">{!!$dialog->vocabulary!!}</div>
                                                                        </div>-->
                                     <textarea id="voc_content" name="vocabulary" style="display: none;" spellcheck="true"> {!!$dialog->vocabulary!!}</textarea>

                                     <div class="click2editvoc" contenteditable="true" spellcheck="true">
                                        {!!$dialog->vocabulary!!}
                                    </div>
                                    <!--<script src="//cdn.ckeditor.com/4.5.3/standard/ckeditor.js"></script>-->

<!--                                    <textarea id="voc_content" name="vocabulary" style="display: none;">
                                    <div id="vocabulary" contenteditable="true">
                                        {!!$dialog->vocabulary!!}
                                    </div>
                                    </textarea>-->
     
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Status
                            </label>
                            <div class="col-sm-10">

                                <input class="js-switch" style="display: none;" data-switchery="true" type="checkbox" name="status" {{(old('status') || $dialog->status) ? 'checked' : '' }} >
                            </div>
                        </div>         
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                updated
                            </label>
                            <div class="col-sm-10">
                                {{$dialog->updated}}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                question
                            </label>
                            <div class="col-sm-10">
                                {{$dialog->question}}
                                <button id="show_hide_q" class="btn btn-primary">Show Questions</button> <br>
                                Delete question to empty and server will delete the question of the dialog.<br>
                                <ol class="questions" style="display: none;">
                                    @if($questions)
                                    @foreach(@$questions as $q)
                                    <li>
                                        <label>Question</label>
                                        <input name="questions[{{$q->id}}]" value="{{$q->question}}" style="width: 100%;" spellcheck="true"/>
                                        <label>Correct</label>
                                        <input name="questions_correct[{{$q->id}}]" value="{{$q->correct}}" style="width: 100%;" spellcheck="true"/>
                                        <label>Answers</label>
                                        @php($answers = @json_decode($q->answers)) 
                                        @if($answers)
                                        @foreach($answers as $an)              
                                        <input name="questions_an[{{$q->id}}][]" value="{{$an}}" style="width: 100%;" spellcheck="true"/>
                                        @endforeach
                                        @else
                                        {{$q->answers}}
                                        <input name="questions_an[{{$q->id}}][]" value="" style="width: 100%;" spellcheck="true"/>
                                        <input name="questions_an[{{$q->id}}][]" value="" style="width: 100%;" spellcheck="true"/>
                                        <input name="questions_an[{{$q->id}}][]" value="" style="width: 100%;" spellcheck="true"/>
                                        <input name="questions_an[{{$q->id}}][]" value="" style="width: 100%;" spellcheck="true"/>
                                        
                                        @endif
                                        <input name="questions_an[{{$q->id}}][]" value="" style="width: 100%;" spellcheck="true"/>
                                        
                                    </li>

                                    @endforeach
                                    @endif
                                </ol>
                                <ul id="place_question">
                                    <li>
                                        <label>Question</label>
                                        <input name="nquestions" value="" style="width: 100%;"/>
                                        <label>Correct</label>
                                        <input name="nquestions_correct" value="" style="width: 100%;"/>
                                        <label>Answers</label>                                                  
                                        <input name="nquestions_an[]" value="" style="width: 100%;"/>                                         
                                        <input name="nquestions_an[]" value="" style="width: 100%;"/>                                         
                                        <input name="nquestions_an[]" value="" style="width: 100%;"/>                                         
                                        <input name="nquestions_an[]" value="" style="width: 100%;"/>                                         
                                        <input name="nquestions_an[]" value="" style="width: 100%;"/>                                         
                                        <input name="nquestions_an[]" value="" style="width: 100%;"/>                                         
                                         
                                    </li>
                                </ul>
                                <button id="add_q" class="btn btn-primary">Add a Question</button>

                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Link
                            </label>
                            <div class="col-sm-10">
                                <a href="{{$dialog->link}}" target="_blank">{{$dialog->link}}</a>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Reports
                            </label>
                            <div class="col-sm-10">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <th>Email</th>
                                        <th>Status </th>
                                        <th> Message</th>
                                    </tr>
                                    @foreach($dialog->reports   as $report)
                                    <tr>
                                        <td>{{$report->email}} </td>
                                        <td style="text-align: center">{{$report->status}} <input class="js-switch" style="display: none;" data-switchery="true" type="checkbox"
                                           data-id="{{ $report->id}}"  name="status{{ $report->id}}" {{ $report->status ? 'checked' : '' }} ></td>
                                        <td>{{$report->message}} </td>
                                         
                                    </tr>

                                    @endforeach
                                </table>
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

@section('search_form')
<form role="search" class="navbar-form-custom" action="{{url('admin/listening/search')}}">
    <div class="form-group">
        <input type="text" placeholder="Search a lesson..." class="form-control" name="idiom" value="{{!empty($search) ? $search : ""}}" id="top-search">
    </div>
</form>
@endsection

@section('content_js')
<!--<script src="{!! asset('assets/ckeditor/ckeditor.js') !!}"></script>-->

<script>
  
//    CKEDITOR.inline('vocabulary', {
//        filebrowserBrowseUrl: '{!! url('public/filemanager/index.html') !!}',
//        customConfig: '',
//        extraPlugins: 'sourcedialog'    
//     });
    $('#add_q').click(function(e){
        e.preventDefault();
        $(this).prop('disabled', true);
        var that = this;
//        console.log($('input[name="nquestions_an[]"]').serializeArray())
        jQuery.ajax({
                url: "{{url('admin/listening/ajax-add-qu')}}",
                type: "POST",
                dataType: 'json',
                data:   { "_token": "{{ csrf_token() }}", dlId: {{$dialog->id}},q:$('input[name="nquestions"]').val(),c:$('input[name="nquestions_correct"]').val(), ans: $('input[name="nquestions_an[]"]').serializeArray() }
            }).done(function (data) {
                 if(data["success"] == false){
                     alert(data["message"]);
                     $(that).prop('disabled', false);
                 }else{
                      location.reload(); 
                 }
            })
            .fail(function () {
                alert("error");
                $(that).prop('disabled', false);
            });
    });
    $('#show_hide_q').click(function(e){e.preventDefault(); $('.questions').toggle(300); })
//    var elem = document.querySelector('.js-switch');
//    var switchery = new Switchery(elem, {color: '#1AB394'});
    var elem = jQuery('.js-switch').each(function (index) {
        new Switchery(this, {color: '#1AB394'});

    });

    jQuery('.js-switch').change(function () {
        var report_id = jQuery(this).data('id');
        if (jQuery(this).is(':checked')) {
            var that = this;
            if (report_id) {
                var that = this;
                jQuery.ajax({
                    url: "{{url('admin/listening/report/fix')}}",
                    type: "GET",
                    dataType: 'json',
                    data: {report_id: report_id}
                }).done(function (data) {
//                    $(that).click();
                })
                        .fail(function () {
                            $(that).click();
                            alert("error");
                        });
            }
        }
    });
    var linkRemoveCat = "{{url('admin/listening/remove-cat')}}";
    var linkAutocompleteCat = "{{url('admin/listening/autocomplete-cat')}}";
    var linkAddCat = "{{url('admin/listening/add-cat')}}";
    var linkRemoveGrammar = "{{url('admin/listening/ajax-remove-grammar')}}";
    var linkAutocompleteGrammar = "{{url('admin/listening/autocomplete-grammar')}}";
    var linkAddGrammar = "{{url('admin/listening/ajax-add-grammar')}}";</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link href="{!! asset('assets/css/plugins/summernote/summernote.css')!!}" rel="stylesheet">
<link href="{!! asset('assets/css/plugins/summernote/summernote-bs3.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/plugins/summernote/summernote.min.js') !!}"></script>

@endsection