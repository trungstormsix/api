@extends('layouts.admin')

@section('content')
<!-- header -->
<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/ielts/article/save') }}">
    {{ csrf_field() }}
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{empty($title) ?  'oCoder' : $title}}</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li>
                    <a href="{{url('admin/ielts')}}">IELTS</a>
                </li>
                @if($article)
                <li>
                    <a href="{{url('admin/ielts/cat'.$article->category)}}">IELTS Category</a>
                </li>
                <li class="active">
                    <strong>{{$article->title}}</strong>
                </li>
                @else
                <li class="active">
                    <strong>Add Article</strong>
                </li>
                @endif
            </ol>
        </div>
        <div class="col-lg-2">
            <br>
            <div class="pull-right tooltip-demo">
                <button class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add new Article"><i class="fa fa-plus"></i> Save</button>
                <a href="{{url('/admin/ielts'.(!empty($article) ? '/cat/'.$article->category : ""))}}" class="btn btn-danger btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cancel Edit"><i class="fa fa-times"></i> Discard</a>

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
                                <input type="hidden" name="id"  value="{{$article ? $article->id : ''}}" />
                                {{$article ? $article->id : ""}}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group">

                            <label class="col-sm-2 control-label">     
                                Title
                            </label>
                            <div class="col-sm-10">

                                <input name="title" class='form-control' value="{{ old('title') ?  old('title') : ($article ? $article->title  :'')}}" />
                            </div>

                        </div>
                        <div class="hr-line-dashed"></div>

						<div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Audio
                            </label>
                            <div class="col-sm-10">
								@if(@$article->audio)
									
									<div class="ckeditor-html5-audio" style="text-align:center">
									<audio controls="controls" src="{{$article->audio}}">&nbsp;</audio>
									</div>

								@endif
                                <input name="audio" class='form-control' value="{{ old('audio') ?  old('audio') : ($article ? $article->audio  :'')}}" />
                            </div>

                        </div>
                        <div class="hr-line-dashed"></div>
						
						
                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Category
                            </label>
                            <div class="col-sm-10">

                                @php ($category = old('category') ? old('category') : ($article ? $article->category : ''))
                                <select class="form-control m-b" name="category">

                                    @foreach($cats as $cat)
                                    <option {{$category == $cat->id  ||  (!$category && $cat->id == Session::get('il_cat_id')) ? 'selected' : ""}} value="{{$cat->id}}">{{$cat->title}}</option>

                                    @endforeach

                                </select>
                            </div>
                        </div>      
                        <div class="hr-line-dashed"></div>



                        <div class="form-group">
                            <label class="col-sm-2 control-label">     
                                Article
                            </label>
                            <div class="col-sm-10">
                                <div class="ibox float-e-margins">
                                    @php(   $art =  old('article')    ?   old('article') :  ($article ? $article->article : ''))
                                    <textarea name="article" style="display: none;">{!! $art !!}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Status
                        </label>
                        <div class="col-sm-10">

                            <input class="js-switch" value="1" style="display: none;" data-switchery="true" type="checkbox" name="status" {{(old('status') || ($article && $article->status)) ? 'checked' : '' }} >
                        </div>
                    </div>         
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Pro
                        </label>
                        <div class="col-sm-10">

                            <input class="js-switch" value="1" style="display: none;" data-switchery="true" type="checkbox" name="is_pro" {{(old('is_pro') || ($article && $article->is_pro)) ? 'checked' : '' }} >
                        </div>
                    </div>         
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            updated
                        </label>
                        <div class="col-sm-10">
                            {{$article ? $article->updated : ''}}
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>


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
<script src="{!! asset('assets/ckeditor/ckeditor.js') !!}"></script>

<script>

var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

elems.forEach(function (html) {
    var switchery = new Switchery(html, {color: '#1AB394'});
});

CKEDITOR.disableAutoInline = true;
// Turn off automatic editor creation first.
CKEDITOR.replace('article', {
    filebrowserBrowseUrl: '{!! url("public/filemanager/index.html") !!}'
});
//@if ($art)
//
//        CKEDITOR.inline('article', {
//        filebrowserBrowseUrl: '{!! url("public/filemanager/index.html") !!}'
//        });
//@else
//        CKEDITOR.replace('article', {
//        filebrowserBrowseUrl: '{!! url("public/filemanager/index.html") !!}'
//        });
//@endif
</script>
@endsection