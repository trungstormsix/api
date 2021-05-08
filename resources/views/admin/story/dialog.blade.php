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
                <a id="delete" class="btn btn-sm btn-danger dim"  href="{{url('admin/story/story/delete/'.$dialog->id)}}"><i class="fa fa-remove"></i> Delete</a>
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
                            <a class="btn btn-sm btn-primary crawl-y-sub" href="http://localhost/laravel/api/admin/story/video/{{$dialog->id}}" target="_blank" ><i class="fa fa-video-camera"></i> Create Video</a>
                            <a class="btn btn-sm btn-primary crawl-y-sub" href="{{url('admin/story/duration/'.$dialog->id)}}" target="_blank" ><i class="fa fa-clock-o"></i> Duration</a>

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
                                <a class="btn btn-sm btn-primary crawl-y-sub" href="https://www.youtube.com/timedtext_editor?v={{$dialog->video_id}}&lang=en&name=&kind=&contributor_id=0&bl=vmp&action_view_track=1&ref=rs&nv=1
" target="_blank" ><i class="fa fa-youtube"></i> Edit Sub</a>
                                <a class="btn btn-sm btn-primary crawl-y-sub" href="https://studio.youtube.com/video/{{$dialog->video_id}}/edit" target="_blank" ><i class="fa fa-youtube"></i> Edit Youtube Video</a>

                                
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
                                                                    <input id="add_cat" data-id="{{$dialog->id}}" />

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
                                {{substr($sub,36, 400)}}
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
<form role="search" class="navbar-form-custom" action="{{url('admin/story/search')}}">
    <div class="form-group">
        <input type="text" placeholder="Search a lesson..." class="form-control" name="idiom" value="{{!empty($search) ? $search : ""}}" id="top-search">
    </div>
</form>
@endsection

@section('content_js')
<!--<script src="{!! asset('assets/ckeditor/ckeditor.js') !!}"></script>-->
<script src="{!! asset('assets/js/plugins/sweetalert/sweetalert.min.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/sweetalert/sweetalert.css')!!}" rel="stylesheet">
<script>
  
    $("#delete").click(function(e){
        e.preventDefault();
        that = this;
        swal({
                title: "Are you sure?",
                text: "Truyện này sẽ bị xóa vĩnh viễn và không thể khôi phục lại.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Xóa thẳng tay",
                closeOnConfirm: false
            }, function (is_confirm) {
                if (is_confirm) {
                    window.location.href = $(that).attr('href');;
                } else {
                    $(that).val(0);
                }
            });
    });
    $('#show_hide_q').click(function(e){e.preventDefault(); $('.questions').toggle(300); })
//    var elem = document.querySelector('.js-switch');
//    var switchery = new Switchery(elem, {color: '#1AB394'});
    var elem = jQuery('.js-switch').each(function (index) {
        new Switchery(this, {color: '#1AB394'});

    });
 
    var linkRemoveCat = "{{url('admin/story/remove-cat')}}";
    var linkAutocompleteCat = "{{url('admin/story/autocomplete-cat')}}";
    var linkAddCat = "{{url('admin/story/add-cat')}}";
//    var linkRemoveGrammar = "{{url('admin/listening/ajax-remove-grammar')}}";
//    var linkAutocompleteGrammar = "{{url('admin/listening/autocomplete-grammar')}}";
//    var linkAddGrammar = "{{url('admin/listening/ajax-add-grammar')}}";</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link href="{!! asset('assets/css/plugins/summernote/summernote.css')!!}" rel="stylesheet">
<link href="{!! asset('assets/css/plugins/summernote/summernote-bs3.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/plugins/summernote/summernote.min.js') !!}"></script>

@endsection