@extends('layouts.admin')

@section('content')
@php( $article = isset($article) ? $article : false)

<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/makeup/article/update') }}">

    <input  type="hidden" name='id' value="{{ $article ? $article->id : '' }}">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{ $article ? "Edit" : 'Create' }} Article</h2>


            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin')}}">Home</a>
                </li>
                <li>
                    <a href="{{url('/admin/articles')}}">Articles</a>
                </li>
                <li class="active">
                    <strong>{{ $article ? "Edit" : 'Create' }} Article</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <br>
            <br>
            <div class="pull-right tooltip-demo">
                <button  class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new Articles"><i class="fa fa-plus"></i> Save</button>
                <a href="{{url('/admin/makeup/articles')}}{{Session::get('mk_cat_id') ? "/".Session::get('mk_cat_id') :''}}" class="btn btn-danger btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cancel Edit"><i class="fa fa-times"></i>Discard</a>
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
                            <input class="form-control" type="text" name='title' value="{{old('title') ? old('title') : ($article? $article->title : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Tac Gia
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='tac_gia' value="{{old('tac_gia') ? old('tac_gia') : ($article? $article->tac_gia : '')}}">
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Thumbnail
                        </label>
                        <div class="col-sm-10">
                            @php ($value = (old('intro_img') ? old('intro_img') : ($article ? $article->intro_img : '')))
                            {!! App\library\OcoderHelper::GenerateIcon($value, 'intro_img', URL::asset("/public/filemanager/index.html"), 'intro_img') !!}
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>   

                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Link
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='link' value="{!!old('link') ? old('link') : ($article? $article->link : '')!!}">
							<br>
							<a href="{!!old('link') ? old('link') : ($article? $article->link : '')!!}" target="_blank">link</a>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>  

                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Content
                        </label>
                        <div class="col-sm-10">
                            <textarea id="editor1" class="form-control" type="text" name='content' >{!! old('content') ? old('content') : ($article ? $article->content : '') !!}</textarea>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>   


                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Category
                        </label>
                        <div class="col-sm-10">

                            <select name="categories_id[]" data-placeholder="Choose a Category..." class="chosen-select" style="width:350px; min-height: 200px" tabindex="2" multiple>
                                @foreach ($categories as $category)	
                                <option value="{{$category->id}}" {{in_array($category->id, $cat_ids) ? "selected='selected'" : ""}}>{{$category->title}}</option>	
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

                            <input class="js-switch" value="1" style="display: none;" data-switchery="true" type="checkbox" name="published" {{(old('published') || $article == false || ($article && $article->published)) ? 'checked' : '' }} >

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
disallowedContent : 'img{width,height}'
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