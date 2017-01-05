@extends('layouts.admin')

@section('content')
@php( $articles = isset($articles) ? $articles : false)

<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/articles/save') }}">

    <input  type="hidden" name='id' value="{{ $articles ? $articles->id : '' }}">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{ $articles ? "Edit" : 'Create' }} Article</h2>


            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin')}}">Home</a>
                </li>
                <li>
                    <a href="{{url('/admin/articles')}}">Articles</a>
                </li>
                <li class="active">
                    <strong>{{ $articles ? "Edit" : 'Create' }} Article</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <br>
            <br>
            <div class="pull-right tooltip-demo">
                <button  class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new Articles"><i class="fa fa-plus"></i> Save</button>
                <a href="{{url('/admin/articles')}}" class="btn btn-danger btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cancel Edit"><i class="fa fa-times"></i>Discard</a>
            </div>
        </div>
    </div>


    {{ csrf_field() }}
    <!--input type="hidden" name="id" value="{{empty($user) ? old('id') : $user->id}}" /-->
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">                
                <div class="ibox-content">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Title
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='title' value="{{old('title') ? old('title') : ($articles? $articles->title : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Alias
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='alias' value="{{old('alias') ? old('alias') : ($articles? $articles->alias : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Thumbnail
                        </label>
                        <div class="col-sm-10">
                            @php ($value = (old('thumbnail') ? old('thumbnail') : ($articles ? $articles->thumbnail : '')))
                            {!! App\library\OcoderHelper::GenerateIcon($value, 'thumbnail', URL::asset("/filemanager/index.html"), 'thumbnail') !!}
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>   

                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Link
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='link' value="{!!old('link') ? old('link') : ($articles? $articles->link : '')!!}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>  

                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Content
                        </label>
                        <div class="col-sm-10">
                            <textarea id="editor1" class="form-control" type="text" name='content' >{!! old('content') ? old('content') : ($articles ? $articles->content : '') !!}</textarea>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>   

                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Excerpt
                        </label>
                        <div class="col-sm-10">
                            <textarea id="editor2" class="form-control" type="text" name='excerpt'>{!! old('excerpt') ? old('excerpt') : ($articles ? $articles->excerpt : '') !!}</textarea>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>   

                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Category
                        </label>
                        <div class="col-sm-10">
                            @php ($category_id = old('categories_id') ? old('categories_id') : ($articles ? $articles->categories_id : ''))
                            <select name="categories_id" data-placeholder="Choose a Country..." class="chosen-select" style="width:350px;" tabindex="2">
                                <option value="0">none</option>	
                                @foreach ($categories_level as $categories_level_1)	
                                @if ($categories_level_1->id == $category_id)		
                                <option value="{{$categories_level_1->id}}" selected="selected">{{$categories_level_1->name}}</option>
                                @else		
                                <option value="{{$categories_level_1->id}}">{{$categories_level_1->name}}</option>
                                @endif
                                @foreach ($categories as $categories_level_2)
                                @if ($categories_level_2->parent_id == $categories_level_1->id)
                                @if ($categories_level_2->id == $category_id)	
                                <option value="{{$categories_level_2->id}}" selected="selected">&nbsp;&nbsp;&nbsp; {{$categories_level_2->name}}</option>
                                @else		
                                <option value="{{$categories_level_2->id}}">&nbsp;&nbsp;&nbsp; {{$categories_level_2->name}}</option>
                                @endif
                                @foreach ($categories as $categories_level_3)
                                @if ($categories_level_3->parent_id == $categories_level_2->id)
                                @if ($categories_level_3->id == $category_id)		
                                <option value="{{$categories_level_3->id}}" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$categories_level_3->name}}</option>
                                @else		
                                <option value="{{$categories_level_3->id}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$categories_level_3->name}}</option>
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
                            Published
                        </label>
                        <div class="col-sm-10">

                            <input class="js-switch" value="1" style="display: none;" data-switchery="true" type="checkbox" name="published" {{(old('published') || $articles == false || ($articles && $articles->published)) ? 'checked' : '' }} >

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
<script src="{!! asset('assets/js/plugins/chosen/chosen.jquery.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/chosen/chosen.css')!!}" rel="stylesheet">

<script>
    CKEDITOR.replace('editor1', {
        filebrowserBrowseUrl: '{{URL::asset("filemanager")}}/index.html',
    });
    CKEDITOR.replace('editor2', {
        filebrowserBrowseUrl: '{{URL::asset("filemanager")}}/index.html',
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