@extends('layouts.admin')

@section('content')
 <form class="form-horizontal" role="form" method="POST" action="{{ URL::route('GrmQuestion.save_question') }}">
 <input  type="hidden" name='id' value="{{ $question ? $question->id : '' }}">
 <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{ $question ? "Edit" : 'Create' }} Question</h2>


            
        </div>
        <div class="col-lg-2">
            <br>
            <br>
            <div class="pull-right tooltip-demo">
                
                @if( $question)
                        <a href="{{ URL::route('GrmQuestion.create_question') }}" type="button" class="btn btn-sm btn-info  dim"><i class="fa fa-plus"></i> New</a>
                @endif       
                <button  class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new Articles"><i class="fa fa-check"></i> Save</button>
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
                            Question
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='question' value="{{old('question') ? old('question') : ($question ? $question->correct : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Correct
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name='correct' value="{{old('correct') ? old('correct') : ($question ? $question->correct : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Answers
                        </label>
                        <div class="col-sm-10">
                           
                            @php($answers = $question ? @json_decode($question->answers) : (old('answers') ? old('answers') : [] )) 
                            @if($answers)
                             
                                @foreach($answers as $an)              
                                <input name="answers[]" value="{{$an}}" style="width: 100%;" spellcheck="true"/>
                                @endforeach
                            @else
                           
                            <input name="answers[]" value="" style="width: 100%;" spellcheck="true"/>
                            <input name="answers[]" value="" style="width: 100%;" spellcheck="true"/>
                            <input name="answers[]" value="" style="width: 100%;" spellcheck="true"/>
                            <input name="answers[]" value="" style="width: 100%;" spellcheck="true"/>

                            @endif
                            <input name="answers[]" value="" style="width: 100%;" spellcheck="true"/>                    
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Type
                        </label>
                         @php($type = old('correct') ? old('correct') : ($question ? $question->type : '1'))
                        <div class="col-sm-10">
                            <select name="type">
                                <option value="1" {{$type == 1 ? "selected='selected'" : ""}}>Text</option>
                                <option value="2" {{$type == 2 ? "selected='selected'" : ""}}>Audio</option>
                                <option value="3" {{$type == 3 ? "selected='selected'" : ""}}>Image</option>
                                <option value="4" {{$type == 4 ? "selected='selected'" : ""}}>Text</option>
                            </select>            
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>   
                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Category
                        </label>
                        <div class="col-sm-10">
                            @php ($cat_ids = old('cat_ids') ? old('cat_ids') : ($cat_ids ? $cat_ids : []))
                            <select multiple  name="cat_ids[]" data-placeholder="Choose a Cat..." class="chosen-select" style="width:350px;" tabindex="2">
                                <option value="0">none</option>	
                                @foreach ($categories as $category)	
                                  		
                                <option value="{{$category->id}}" {{in_array($category->id, $cat_ids) ? "selected='selected'" : ""}}>{{$category->title}}</option>                               
                                		
                                @endforeach
                            </select>
                            <br>
                             
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>   
                    
                     
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">   
                            Published
                        </label>
                        <div class="col-sm-10">

                            <input class="js-switch" value="1" style="display: none;" data-switchery="true" type="checkbox" name="published" {{(old('published') || ( $question && $question->published)) ? 'checked' : '' }} >

                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>  
                    
                     <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Explaination
                        </label>
                        <div class="col-sm-10">
                                <textarea id="editExplaination" class="form-control" type="text" name='explanation' >{!! old('explanation') ? old('explanation') : ($question ? $question->explanation : '') !!}</textarea>

                         </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                </div>
                <button class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add new playlist" style="position: fixed; bottom: 30px;right: 42px;">
                            <i class="fa fa-check"></i> Save</button>
            </div>
        </div>
    </div>
 </form>
@endsection
@section("content_js")
<script src="{!! asset('assets/ckeditor/ckeditor.js') !!}"></script>

<script src="{!! asset('assets/js/plugins/sweetalert/sweetalert.min.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/sweetalert/sweetalert.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/plugins/chosen/chosen.jquery.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/chosen/chosen.css')!!}" rel="stylesheet">

<script>
   CKEDITOR.replace('editExplaination', {
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
</script>
@endsection
