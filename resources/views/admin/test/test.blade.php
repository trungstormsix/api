@extends('layouts.admin')

@section('content')




<div class="row wrapper border-bottom white-bg page-heading m-b">
    <div class="col-lg-10">
        <h2><a href="{{$test->link}}" target="_blank">{{empty($test) ?  'oCoder Tests' : $test->title}}</a></h2>

    </div>
    <div class="col-lg-2">
        <br>
 
       <div class="pull-right tooltip-demo">
            <a href="{{ url('admin/tests/test/trim/'.$test->id)}}" class="btn btn-sm btn-primary dim" data-toggle="tooltip" data-placement="top" title="" data-original-title="Trim Test"><i class="fa fa-plus"></i> Trim</a>
         </div>
    </div>
</div>
 
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">                
            <div class="ibox-content">
                    @php($q = 1)
                    @foreach($groups as $group)
                   
                        <div class="row">
                            <div class="col-lg-12">
                                <p><b>{!!$group->text!!}</b></p>
                                @if($group->audio)
                                {!!$group->audio!!}
                                <p> <audio controls>
                                        <source src="{{$group->audio}}" type="audio/mpeg">
                                    </audio>
                                </p>
                                @endif
                                @if($group->image)
                                <p><img src="{!!$group->image!!}" /></p>
                                @endif


                                @php($questions = $group->questions()->get())                                
                                @foreach($questions as $question)
                                    Question {{$q++}} - Id: {{$question->id}}
									<a  href="{{ URL::route('question.delete',$question->id)}}" class="delete btn btn-danger btn-xs m-l-sm" >Delete</a>
                                    <div style="padding-left: 10px">
                                        <b>{!!$question->question.'<br>'!!}</b>
                                        @php($answers = json_decode($question->answers))
                                        @foreach($answers as $answer)
                                            @if($answer == $question->correct)
                                                <span style="color: red">{{$answer}}</span><br>
                                            @else
                                                {{$answer}}<br>
                                            @endif
                                        @endforeach
                                        <label>Explaination</label>
                                        <div>
                                        <button  data-qid="{!!$question->id!!}" class="edit btn btn-primary btn-xs m-l-sm"   type="button">Edit</button>
                                        <button  data-qid="{!!$question->id!!}" class="save btn btn-primary  btn-xs"   type="button">Done</button>
                                         <div class="ibox-content no-padding">
                                            <div data-qid="{!!$question->id!!}" class="jpclick2edit dialog{!!$question->id!!} wrapper p-md">{!!$question->explaination!!}</div>
                                        </div>
                                        </div>
										<label>Giải Thích</label>
                                        <div>
                                        <!-- <button  data-qid="{!!$question->id!!}" class="edit btn btn-primary btn-xs m-l-sm"   type="button">Edit</button>
                                        <button  data-qid="{!!$question->id!!}" class="save btn btn-primary  btn-xs"   type="button">Done</button> -->
                                         <div class="ibox-content no-padding">
                                            <div data-qid="{!!$question->id!!}" class="jpclick2edit dialog_gt{!!$question->id!!} wrapper p-md">{!!$question->giai_thich!!}</div>
                                        </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                           
                        </div>
                    
                        <div class="hr-line-dashed"></div>

                    
                    @endforeach
                

                <br>
                <div class="hr-line-dashed"></div>   
            </div>
        </div>
    </div>
</div>

@endsection
@section("content_js")
 <script src="{!! asset('assets/js/plugins/chosen/chosen.jquery.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/chosen/chosen.css')!!}" rel="stylesheet">

<link href="{!! asset('assets/css/plugins/summernote/summernote.css')!!}" rel="stylesheet">
<link href="{!! asset('assets/css/plugins/summernote/summernote-bs3.css')!!}" rel="stylesheet">
<script src="{!! asset('assets/js/plugins/summernote/summernote.min.js') !!}"></script>

<script>
 $('.delete').click(function(){return  confirm("Chắc chắn muốn xóa câu hỏi này chứ?  \nCâu hỏi bị xóa sẽ không khôi phục lại được!");});
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{!!csrf_token()!!}"
        }
    }); 
     jQuery('.edit, .jpclick2edit').click(function () {
        var qid = $(this).data("qid");
        jQuery('.dialog'+qid+",.dialog_gt"+qid).summernote({focus: true, styleWithSpan: false});
    }
    )
    jQuery('.save').click(function () {
		 
        var qid = $(this).data("qid");        
        var aHTML = jQuery('.dialog'+qid).code(); //save HTML If you need(aHTML: array).
        var gtHTML = jQuery('.dialog_gt'+qid).code(); //save HTML If you need(aHTML: array).
		var that = $(this);
		that.text("Saving...").attr("disabled", true);
        jQuery.ajax({
            url: "{!! URL::route('tests.ajax_save_question') !!}",
            type: "POST",
            dataType: 'json',
            data: {qid: qid, explaination: aHTML, giai_thich: gtHTML}
        }).done(function (data) {
            //jQuery(that).parent().remove();
			that.text("Done").removeAttr("disabled");
			jQuery('.dialog'+qid).destroy();
			jQuery('.dialog_gt'+qid).destroy();
        })
                .fail(function (e) {
                    alert("error" + e.toString());
                });
                
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