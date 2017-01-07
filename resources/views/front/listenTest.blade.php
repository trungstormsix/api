@extends('layouts.front')

@section('content')
<div class="container">
	<div class="row features">
	    <div class="col-lg-12">
	        <div class="ibox float-e-margins">
	            <div class="col-lg-12 text-center">
	                <h1>Listening English</h1>
	            </div>
	            <div class="ibox-content">
	                <div class="panel panel-success">
	                	<div class="panel-heading"><h2>{{$dialogs->title}}</h2></div>
	                	<div class="panel-body">
	                		<audio controls class="m-b">
								<source src="{!! asset('assets/audio/'.$dialogs->audio) !!}">
							</audio>
							<form action="" class="form-horizontal">
								@foreach ($questions as $question)
									<div class="m-b has-warning">
										<label for="">{{$question->question}}</label>
										@php $answers = json_decode($question->answers); @endphp
										@foreach ($answers as $answer)
											@if ($answer == $question->correct)
												<div class="i-checks"><label class="correct"> <input type="radio" value="{{$answer}}" name="{{$question->id}}"> {{$answer}} <i class="fa fa-check text-success" style="display: none;"> Correct</i></label></div>
											@else
												<div class="i-checks"><label> <input type="radio" value="{{$answer}}" name="{{$question->id}}"> {{$answer}} </label></div>
											@endif
										@endforeach
									</div>
								@endforeach
							</form>
							<button type="button" class="btn btn-primary  m-t check_result">Check Result</button>
							<div class="test_dialog m-t">
								<h3>Dialog</h3>
								<p>{!! $dialogs->dialog !!}</p>								
							</div>
	                	</div>
	                </div>

	            </div>
	        </div>

	    </div>
	</div>
</div>
@endsection