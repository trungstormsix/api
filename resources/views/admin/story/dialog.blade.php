@extends('layouts.admin')

@section('content')
<!-- header -->
<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/story/story/save') }}">
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
                                <span class="btn btn-primary">{{$dialog->id}}</span>  <a href="{{url('admin/story/story/'.($next ? $next->id : 1))}}" class="btn btn-success"  >
                                        <b>Next {{$next? $next->id : ""}}</b>
                                    </a>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                Title
                            </label>
                            <div class="col-sm-10"><input class="form-control" name="title" value="{{$dialog->title}}" /></div>     
                        </div>
                        <div class="hr-line-dashed"></div>
                        
                         <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                Video Youtube
                            </label>
                            <div class="col-sm-3"><input class="form-control" name="video_id" value="{{$dialog->video_id}}" /><br>
                                <a class="btn btn-sm btn-primary" href="{{url("/ysubs/".$dialog->video_id)}}.txt" target="_blank" >Sub</a> 
                                <a class="btn btn-sm btn-primary crawl-y-sub" href="{{url("/admin/story/crawl-y-sub?id=").$dialog->id}}" target="_blank" ><i class="fa fa-download"></i> Crawl Youtube Sub</a>

                            </div>     
                             <div class="col-sm-7">
                                 <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Cats
                            </label>
                            <div class="col-sm-10">
                                <div id="cat_container" style="display: inline-block">
                                    @foreach ($dialog->types as $cat)
                                    <span class="alert alert-warning remove-cat" style="display: inline-block;">
                                        <button aria-hidden="true" data-cat="{{$cat->id}}" data-main="{{$dialog->id}}" class="close" type="button">×</button>
                                        <a class="cat-link" target="_blank" href="http://ocodereducation.com/admin/stories/dialogs/st-{{$cat->id}}">{{$cat->title}}</a> 
                                    </span>
                                    @endforeach
                                </div>
                             </div>
                        </div>  
                             </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                audio
                            </label>
                            <div class="col-sm-1">
                                {!!$dialog->audio!!}
                            </div> 
                            <div class="col-sm-9">
                                <audio class="form-control" controls="">
                                    <source src="http://dogiadungchinhhang.com/audios/estory/{!!$dialog->audio!!}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        </div>       
                        <div class="hr-line-dashed"></div>

                       
                        

                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Dialog
                            </label>
                            <div class="col-sm-10">
                                 <div class="ibox float-e-margins">
                                {{substr($sub,36, 100)}}
                                 </div>
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
                                Status
                            </label>
                            <div class="col-sm-2">

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
                                Link
                            </label>
                            <div class="col-sm-10">
                                <a href="{{$dialog->link}}" target="_blank">{{$dialog->link}}</a>
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