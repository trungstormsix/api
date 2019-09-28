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
                    <strong>{{$voc->title}}</strong>
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
                                <input type="hidden" name="id" value="{{$voc->id}}" />
                                {{$voc->id}}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                Word
                            </label>
                            <div class="col-sm-10">{{$voc->en_us_type}} {{$voc->en_us_pr}}<br><input name="title" value="{{$voc->en_us}}" /></div>     
                        </div>
                        <div class="hr-line-dashed"></div>                      

                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Cats
                            </label>
                            <div class="col-sm-10">
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
@endsection
 

@section('content_js')
<script src="{!! asset('assets/ckeditor/ckeditor.js') !!}"></script>

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
    var linkAutocompleteGrammar = "";
    
 </script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link href="{!! asset('assets/css/plugins/summernote/summernote.css')!!}" rel="stylesheet">
<link href="{!! asset('assets/css/plugins/summernote/summernote-bs3.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/plugins/summernote/summernote.min.js') !!}"></script>

@endsection