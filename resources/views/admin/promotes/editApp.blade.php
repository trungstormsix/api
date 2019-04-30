@extends('layouts.admin')

@section('content')
<!-- header -->
<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/promote/app') }}">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{empty($title) ?  'oCoder' : $title}}</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>

                <li class="active">
                    <strong>Edit App</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <br>
            <br>
            <div class="pull-right tooltip-demo">
                <button   class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new playlist"><i class="fa fa-plus"></i> Save</button>
                <a href="{{url('/admin/promote')}}" class="btn btn-danger btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cancel Edit"><i class="fa fa-times"></i> Discard</a>
            </div>
        </div>
    </div>


    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{empty($app) ? old('id') : $app->id}}" />
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">                
                <div class="ibox-content">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Title
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='title' value="{{old('title') ? old('title') : $app->title }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Package
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='package' value="{{old('package') ? old('package') : $app->package }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            <img alt="Image" style="max-width: 130px  " class="img-circle circle-border" src="http://ocodereducation.com{{$app->image}}">
                        </label>
                        <div class="col-sm-10">
                            {!! App\library\OcoderHelper::GenerateIcon((old('image') ? old('image') : $app->image), 'image', url("public/filemanager/index.html")) !!}
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Status
                        </label>
                        <div class="col-sm-10">
                            <input class="js-switch" value="1" style="display: none;" data-switchery="true" type="checkbox" name="status" {{(old('status') || $app->status) ? 'checked' : '' }} >
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Description
                        </label>
                        <div class="col-sm-10">
                            <textarea name="description" style="display: none;">
                                    <div id="description" contenteditable="true">
                                        @php(   $art =  old('description')    ?   old('description') :  $app->description)
                                        {!! $art !!}
                                    </div>
                            </textarea>                        
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Publish
                        </label>
                        <div class="col-sm-10">
                            <div class="input-daterange input-group" id="datepicker">
                                <input type="text" class="input-sm form-control" name="publish_up" value="{{$app ? date("Y-m-d",strtotime($app->publish_up)) : ''}}">
                                <span class="input-group-addon">to</span>
                                <input type="text"  class="input-sm form-control" name="publish_down" value="{{$app ? date("Y-m-d",strtotime($app->publish_down)) : ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Cat
                        </label>
                        <div class="col-sm-10">
                            <select  name="group_id" data-placeholder="Choose a Group..." class="form-control m-b chosen-select" style="width:350px;" tabindex="2">
                                @foreach($groups as $group)
                                <option {{($app && $app->group_id == $group->id) ? "selected" : (Session::get('group_id')  == $group->id ? "selected" :"")}} value='{{$group->id}}'>
                                    {{$group->title}}
                                </option>
                                @endforeach
                            </select>               
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Adrate
                        </label>
                        <div class="col-sm-10">
                            <input type="text" name="ad_rate" value="{{old('ad_rate')    ?   old('ad_rate') :   $app->ad_rate}}" class="dial m-r-sm" data-fgColor="#1AB394" data-width="85" data-height="85" />
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Key Start App
                        </label>
                        <div class="col-sm-10">
                            <input name="key_startapp" value="{{old('key_startapp')    ?   old('key_startapp') :   $app->key_startapp}}"
                         </div>
                    </div>      
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('content_js')
<script src="{!! asset('assets/js/plugins/jsKnob/jquery.knob.js') !!}"></script>
<script src="{!! asset('assets/ckeditor/ckeditor.js') !!}"></script>
<script src="{!! asset('assets/js/plugins/chosen/chosen.jquery.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/chosen/chosen.css')!!}" rel="stylesheet">
<script>
var elem = document.querySelector('.js-switch');
var switchery = new Switchery(elem, {color: '#1AB394'});
$('.input-daterange').datepicker({
keyboardNavigation: false,
        forceParse: false,
        autoclose: true,
        format: "yyyy-mm-dd"

        });
$(".dial").knob();
CKEDITOR.disableAutoInline = true;
// Turn off automatic editor creation first.
CKEDITOR.inline('description', {
filebrowserBrowseUrl: '{!! url("public/filemanager/index.html") !!}'
        });
//choosen
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