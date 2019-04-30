@extends('layouts.admin')

@section('content')
<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/pronunciation/save_voc') }}">
    <input  type="hidden" name='id' value="{{ $voc ? $voc->id : '' }}">
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{ $voc ? "Edit" : 'Create' }} Pronunciation Vocabulary</h2>

            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin')}}">Home</a>
                </li>
                <li>
                    <a href="{{url('/admin/pronunciation')}}">Pronunciation</a>
                </li>
                <li class="active">
                    <strong>{{ $voc ? "Edit" : 'Create' }} Voc</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <br>
            <br>
            <div class="pull-right tooltip-demo">
                <button  class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new Articles"><i class="fa fa-plus"></i> Save</button>

                @if( $voc)
                <a href="{{ URL::route('pronunciation.crawl_voc', $voc->id) }}" type="button"  id="crawl_voc"  class="btn btn-sm btn-primary dim">Crawl</a>
                <a href="{{ url('admin/pronunciation/create_voc')}}" type="button" class="btn btn-sm btn-primary dim">New</a>
                @endif    
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
                            Category
                        </label>
                        <div class="col-sm-10">
                            @php ($cat_id = old('cat_id') ? old('cat_id') : ($voc ? $voc->cat_id : $cat_id))
                            <select name="cat_id" data-placeholder="Choose a Cat..." class="chosen-select" style="width:350px;" tabindex="2">
                                <option value="0">none</option>	
                                @foreach ($categories as $categories_level_1)	
                                @if ($categories_level_1->id == $cat_id)		
                                <option value="{{$categories_level_1->id}}" selected="selected">{{$categories_level_1->title}}</option>
                                @else		
                                <option value="{{$categories_level_1->id}}">{{$categories_level_1->title}}</option>
                                @endif
                                		
                                @endforeach
                            </select>
                            <br>
                            {{old('cat_id ') ? old('cat_id ') : ($voc? $voc->cat_id  : '')}}
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>   
                    
                    
                     
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Vocabulary
                        </label>
                        <div class="col-sm-10">
                                <textarea id="editVoc" class="form-control" type="text" name='english' >{!! old('english') ? old('english') : ($voc ? $voc->english : '') !!}</textarea>

                            {{old('english') ? old('english') : ($voc? $voc->english : '')}}
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>




                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Pronunciation
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='pinyin' value="{{old('pinyin') ? old('pinyin') : ($voc? $voc->pinyin  : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Type
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='type' value="{{old('type') ? old('type') : ($voc? $voc->type : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    

                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            MP3
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='mp3_link' value="{{old('mp3_link') ? old('mp3_link') : ($voc? $voc->mp3_link : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            VI
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='vi' value="{{old('vi') ? old('vi') : ($voc? $voc->vi : '')}}">
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
<script src="{!! asset('assets/js/plugins/sweetalert/sweetalert.min.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/sweetalert/sweetalert.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/ckeditor/ckeditor.js') !!}"></script>
<script src="{!! asset('assets/js/plugins/chosen/chosen.jquery.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/chosen/chosen.css')!!}" rel="stylesheet">
<script>
    CKEDITOR.replace('editVoc', {
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
            
 $("#crawl_voc").click(function(){
    that = this;     
    swal({
        title: "Are you sure?",
        text: "Các thông số cũ có thể bị thay đổi mà không thể khôi phục lại được!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, Crawl!",
        closeOnConfirm: false
    }, function (is_confirm) {
        if(is_confirm)
            window.location = $(that).attr("href");
          
    });
    return false;
});
</script>
@endsection
