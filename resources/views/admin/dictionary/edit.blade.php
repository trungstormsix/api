@extends('layouts.admin')

@section('content')
@php( $word = isset($word) ? $word : false)

<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/dictionary/save') }}">

    <input  type="hidden" name='id' value="{{ $word ? $word->id : '' }}">

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{{ $word ? "Edit" : 'Create' }} <a href={{$link}} target="_blank">Word - {{$word->word->word}}</a></h2>
            
            <a href="{{ url('/admin/dictionary/refresh/'.$word->id )}}" target="_blank">Refresh - {{$word->word->word}}</a>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/admin')}}">Home</a>
                </li>

            </ol>
        </div>
        <div class="col-lg-2">
            <br>
            <br>
            <div class="pull-right tooltip-demo">
                <button  class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="Add new Articles"><i class="fa fa-plus"></i> Save</button>
                <a href="{{ URL::previous() }}" class="btn btn-danger btn-sm dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cancel Edit"><i class="fa fa-times"></i>Discard</a>
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
                            <input class="form-control" type="text" name='word' value="{{old('word') ? old('word') : ($word? $word->word->word : '')}}">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Meaning
                        </label>
                        <div class="col-sm-10">
                            <div class="meaning" id='meaning_wrapper'>

                                @php($i = 0)
                                @foreach(json_decode($word->mean) as $mean)
                                @php($m = @$mean->phrase ? $mean->phrase : $mean->meanings[0])
                                @php($type = @$mean->phrase ? "phrase" : "meaning")
                                <div class="ibox float-e-margins collapsed">
                                    <div class="ibox-title">
                                        <h5>{{$i.". ".$m->text}} </h5>
                                        <div class="ibox-tools">
                                            <a class="collapse-link">
                                                <i class="fa fa-chevron-down"></i>
                                            </a>                                             

                                            <a class="close-link-dic">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="ibox-content">
                                        <div class="meaninges-wrapper">
                                            <div class="form-group">
                                                 
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <textarea class="form-control phone" type="text" name="meaning[]"  placeholder="Mean">{{ @old('meaning')[$i] ? @old('meaning')[$i]  : ($mean ? $m->text : '' )}}</textarea>                            
                                                    </div>
                                                    <div class="form-group">
                                                        <input class="form-control phone" type="text" name="lang[]" value="{{ @old('lang')[$i] ? @old('lang')[$i]  : ($mean ? $m->language : '' )}}" placeholder="Lang">                            
                                                    </div>
                                                    <div class="form-group">
                                                        <select name="type[]">
                                                            <option value="phrase" {{@$mean->phrase ? "selected" : ""}}>Phrase</option>
                                                            <option value="meaning" {{@$mean->phrase ? "" : "selected"}}>Meaning</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="form-group submeanings_wrapper" id="submeanings_wrapper_{{$i}}">
                                                        @if($type == "phrase" && @$mean->meanings)

                                                        @foreach($mean->meanings as $meaning)

                                                        <div class="form-group meaninges-wrapper">
                                                            <div class="col-sm-1">
                                                                <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                                                <br>
                                                                <a href="#" class="remove">
                                                                    <span class="glyphicon glyphicon-remove"></span>Remove
                                                                </a>
                                                            </div>
                                                            <div class="col-sm-11">
                                                                <div class="form-group">
                                                                    <textarea class="form-control phone" type="text" name="meaning_sub[{{$i}}][mean][]"  placeholder="Mean">{{($meaning ? $meaning->text : '' )}}</textarea>                            
                                                                </div>
                                                                <div class="form-group">
                                                                    <input class="form-control phone" type="text" name="meaning_sub[{{$i}}][lang][]" value="{{ ($meaning ? $meaning->language : '' )}}" placeholder="Lang">                            
                                                                 </div>
                                                                <div class="hr-line-dashed"></div>
                                                            </div>

                                                        </div>

                                                        @endforeach
                                                        @endif
                                                    </div>
                                                    <span class="add-sub-meaning phone-type" {!! $type == "phrase" ? "" : "style='display: none'"!!} data-i="{{$i}}">+ Add Sub Meaning</span>
                                                    
                                                    <div class="submeaning_for_add" style="display: none">
                                                        <div class="form-group meaninges-wrapper" id="submeaning_for_insert_{{$i}}">
                                                            <div class="form-group meaninges-wrapper">
                                                                <div class="col-sm-1">
                                                                    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                                                    <br>
                                                                    <a href="#" class="remove">
                                                                        <span class="glyphicon glyphicon-remove"></span>Remove
                                                                    </a>
                                                                </div>
                                                                <div class="col-sm-11">
                                                                    <div class="form-group">
                                                                        <textarea class="form-control phone" type="text" name="meaning_sub[{{$i}}][mean][]"  placeholder="Mean"></textarea>                            
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <input class="form-control phone" type="text" name="meaning_sub[{{$i}}][lang][]" value="" placeholder="Lang">                            
                                                                     </div>
                                                                    <div class="hr-line-dashed"></div>
                                                                </div>

                                                            </div>        
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <div class="hr-line-dashed"></div>
                                        </div>

                                    </div>
                                </div>
                                @php($i++)
                                @endforeach                                 


                            </div>
                            <div  id='meaning_for_insert' style="display: none">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h5>New Meaning</h5>
                                        <div class="ibox-tools">
                                            <a class="collapse-link">
                                                <i class="fa fa-chevron-down"></i>
                                            </a>                                             

                                            <a class="close-link-dic">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="ibox-content">
                                <div class="meaninges-wrapper">
                                    <div class="form-group">                                         
                                        <div class="col-sm-11">
                                            <div class="form-group">
                                                <input class="form-control phone" type="text" name="meaning[]" value="" placeholder="Mean">                            
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control phone" type="text" name="lang[]" value="" placeholder="Lang">                            
                                            </div>
                                            <div class="form-group">
                                                <select name="type[]">
                                                    <option value="phrase" >Phrase</option>
                                                    <option value="meaning"  >Meaning</option>
                                                </select>
                                            </div>
                                            <span class="add-sub-meaning phone-type" data-i="{{$i}}">+ Add Sub Meaning</span>
                                                    
                                                    <div class="submeaning_for_add" style="display: none">
                                                        <div class="form-group meaninges-wrapper" id="submeaning_for_insert_{{$i}}">
                                                            <div class="form-group meaninges-wrapper">
                                                                <div class="col-sm-1">
                                                                    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                                                    <br>
                                                                    <a href="#" class="remove">
                                                                        <span class="glyphicon glyphicon-remove"></span>Remove
                                                                    </a>
                                                                </div>
                                                                <div class="col-sm-11">
                                                                    <div class="form-group">
                                                                        <textarea class="form-control phone" type="text" name="meaning_sub[{{$i}}][mean][]"  placeholder="Mean"></textarea>                            
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <input class="form-control phone" type="text" name="meaning_sub[{{$i}}][lang][]" value="" placeholder="Lang">                            
                                                                     </div>
                                                                    <div class="hr-line-dashed"></div>
                                                                </div>

                                                            </div>        
                                                        </div>
                                                    </div>
                                        </div>


                                    </div>
                                    <div class="hr-line-dashed"></div>
                                </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">     
                                <span class="add-meaning phone-type">+ Add Another</span>
                            </div>

                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-2 control-label">     
                            Example
                        </label>
                        <div class="col-sm-10">
                            <div class="examples" id='examples_wrapper'>

                                @php($i = 0)
                                @foreach(json_decode($word->example) as $example)
                                <div class="example-wrapper">
                                    <div class="form-group">
                                        <div class="col-sm-1">
                                            <span class="ui-icon ui-icon-arrowthick-2-n-s"></span><br>
											<span>{{$i+1}}</span>
                                            <a href="#" class="remove">
                                                <span class="glyphicon glyphicon-remove"></span>Remove
                                            </a>
                                        </div>
                                        <div class="col-sm-11">

                                            <div class="form-group">
                                                <textarea class="form-control example" type="text" name="example[]"  placeholder="Mean">{{ @old('example')[$i] ? @old('example')[$i]  : ($example ? $example->first : '' )}}</textarea>                           
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control phone" type="text" name="exampleMean[]" value="{{ @old('exampleMean')[$i] ? @old('exampleMean')[$i]  : ($example ? $example->second : '' )}}" placeholder="Mean">                            
                                            </div>

		
                                        </div>


                                    </div>
                                    <div class="hr-line-dashed"></div>
                                </div>
								@php($i++)
                                @endforeach
                            </div>
                            <div class="form-group">     
                                <span class="add-example phone-type">+ Add Another Example</span>
                            </div>
                            <div id="example_for_insert" style="display: none">
                                <div class="example-wrapper">
                                    <div class="form-group">
                                        <div class="col-sm-1">
                                            <span class="ui-icon ui-icon-arrowthick-2-n-s"></span><br>
                                            <a href="#" class="remove">
                                                <span class="glyphicon glyphicon-remove"></span>Remove
                                            </a>
                                        </div>
                                        <div class="col-sm-11">

                                            <div class="form-group">
                                                <input class="form-control example" type="text" name="example[]" value="" placeholder="Mean">                            
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control phone" type="text" name="exampleMean[]" value="" placeholder="Mean">                            
                                            </div>


                                        </div>


                                    </div>
                                    <div class="hr-line-dashed"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <button class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add new playlist" style="position: fixed; bottom: 40px;right: 42px;">
                        <i class="fa fa-plus"></i> Save</button>
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link href="{!! asset('assets/css/plugins/summernote/summernote.css')!!}" rel="stylesheet">
<link href="{!! asset('assets/css/plugins/summernote/summernote-bs3.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/plugins/summernote/summernote.min.js') !!}"></script>
<script>
    var old_index = new_index = 0;
    function updateInput(i, value){
         $("#submeanings_wrapper_"+i+" input, " +"#submeanings_wrapper_"+i+" textarea, " + "#submeaning_for_insert_"+i+" input, " +"#submeaning_for_insert_"+i+" textarea").each(function(){
                $(this).attr('name',$(this).attr('name').replace(i,value));
               
        });
        $(".add-sub-meaning[data-i="+i+"]").attr("data-i",value);
        $("#submeanings_wrapper_"+i).attr("id","submeanings_wrapper_"+(value));
        $("#submeaning_for_insert_"+i).attr("id","submeaning_for_insert_"+value); 
//        $("#meaning_wrapper .ibox .ibox-title h5").eq(i) .text(i);
     }
    $("body").on("click",'.close-link-dic',function () {         
        var i = $("#meaning_wrapper .ibox").index($(this).parents(".ibox")) + 1;
        
        for(i;i < $("#meaning_wrapper .ibox").length; i++){
            updateInput(i, i-1) ;
        }
        var content = $(this).closest('div.ibox');
        content.remove();
        return false;
    });
    $('#meaning_wrapper').on("click",'.remove', function () {        
        $(this).parents(".meaninges-wrapper").first().remove();
        return false;
    });
    $('#meaning_wrapper').on("click",'.remove', function () {        
        $(this).parents(".meaninges-wrapper").first().remove();
        return false;
    });
    $('#examples_wrapper').on("click",'.remove', function () {        
        $(this).parents(".example-wrapper").remove();
        return false;
    });
    $('.add-meaning').click(function () {        
        $('#meaning_wrapper').append($('#meaning_for_insert').html());
     });
     $('.add-example').click(function () {        
        $('#examples_wrapper').append($('#example_for_insert').html());
     });   
    $('.add-sub-meaning').click(function () {
          var i = $(this).data("i");
        $('#submeanings_wrapper_'+i).append($('#submeaning_for_insert_'+i).html());
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
            
    $( function() {
     var old_index = 0;
     var new_index = 0;
    $( "#meaning_wrapper" ).sortable({
        handle: ".col-sm-1,.ibox-title",
        cancel: ".portlet-toggle",
        placeholder: "portlet-placeholder ui-corner-all",
        start: function(e, ui) {
            ui.placeholder.height(ui.helper.outerHeight());
             
            old_index  = ui.item.index();
        },
        update: function(e, ui){
            new_index  = ui.item.index();
           
            var t = 1;
             if(old_index < new_index){
                updateInput(old_index,"temp");
                t = -1 ;   
                for(var i = old_index + 1; i <= new_index; i++){
                    value = i+t;                
                    updateInput(i,value);
                }       
                updateInput("temp",new_index);

             } else{
               updateInput(old_index,"temp");
                for(var i = old_index-1; i >= new_index; i--){
                    value = i+t;                
                    updateInput(i,value);
                } 
                updateInput("temp",new_index);
                 
             }
            
            

        } 
                                    
        
    });
    
    // Sort the children
    $(".submeanings_wrapper").sortable({
        handle: ".col-sm-1",
        cancel: ".portlet-toggle",
        placeholder: "portlet-placeholder ui-corner-all",
        start: function(e, ui) {
            ui.placeholder.height(ui.helper.outerHeight());
        }
         
    });
    $( "#examples_wrapper" ).sortable({
        handle: ".col-sm-1",
        cancel: ".portlet-toggle",
        placeholder: "portlet-placeholder ui-corner-all",
 
         start: function(e, ui) {
            ui.placeholder.height(ui.helper.outerHeight());
        }
    });
    
 //        $( "#meaning_wrapper, #examples_wrapper" ).disableSelection();
    });
</script>
 
@endsection