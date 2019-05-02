@extends('layouts.admin')

@section('content')
 
<div id="home_categories" >
    <div class="ibox-content">
        <a href="{{URL::route('GrmQuestion.create_question') }}" type="button" class="btn btn-primary btn-lg">Add new Question</a>
        <div class="table-responsive">
        <table class="table table-stripped  ">
            <thead>
                <tr>
                    <th >Id</th>
                    <th >Question</th>
                    <th >Answers</th>
                    <th >Correct</th>
                    <th >Type</th>
                    <th >Published</th>
                    <th >Explaination</th>
                    
                    <th >&nbsp;</th>
                </tr>
                </tr>
            </thead>
            <tbody>  
                @php($t = "")
                @foreach ($questions as $question)
                <tr >	
                   
                    <td > {{$question->id}} </td>
                    <td> {{$question->question}}</td>
                    <td > {{$question->answers}} </td>
                    <td > {{$question->correct}} </td>
                    <td > {{$question->type}} </td>
                    <td> <input class="js-switch" style="display: none;" data-switchery="true" type="checkbox"
                                           data-id="{{ $question->id}}"  name="status{{ $question->id}}" {{ $question->published ? 'checked' : '' }} >
                    </td>
                    <td > {{ substr(  strip_tags($question->explanation),0,100) }}</td>
                     
                    <td style="width: 262px;"> 
                        <a href="{{ URL::route('GrmQuestion.edit_question', $question->id) }}" class="btn btn-info">Update</a>                                               
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="menu_pagination"> </div>
        </div>
    </div>
</div>

@endsection
@section("content_js")
<script src="{!! asset('assets/js/plugins/dataTables/datatables.min.js') !!}"></script>
<link href="{!! asset('assets/css/plugins/dataTables/datatables.min.css')!!}" rel="stylesheet">
 
<style>
    tr.title{
        background:  #2f4050;
        font-weight: bold;
        color: #fff;
        padding-top: 10px;
    }
    tr.title td{
        
        padding-top: 20px !important;
    }
    </style>
<script>
var elem = jQuery('.js-switch').each(function (index) {
    new Switchery(this, {color: '#1AB394'});

});


 jQuery('.js-switch').change(function () {
        var question_id = jQuery(this).data('id');
        var published = jQuery(this).is(':checked') ? 1 : 0;
          var checked = jQuery(this).is(':checked');
             var that = this;
            if (question_id) {
                var that = this;
                jQuery.ajax({
                    url: "{{ URL::route('GrmQuestion.ajax_publish_question') }}",
                    type: "GET",
                    dataType: 'json',
                    data: {question_id: question_id, published: published}
                }).done(function (data) {
   
                }) 
                .fail(function () {
                

                    alert("error");
                });
            }
        
    })

</script>
@endsection
