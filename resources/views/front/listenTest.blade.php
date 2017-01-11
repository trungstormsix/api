@extends('layouts.page-front')

@section('content')
<div class="container">
	<div class="row features">
		<div class="col-lg-12">			
            <div class="text-center">
                <h1>Listening English</h1>
            </div>	  
		</div>
	    <div class="col-lg-8">
	        <div class="ibox float-e-margins">
	            <div class="ibox-content">
		            @php if ($dialogs->vocabulary != null && !is_object(json_decode($dialogs->vocabulary))) { @endphp
		            	<div class="lq_block lq_block_show lq_clicked">
		            		<div class="lq_block_click">
		            			<div class="lq_block_cl">
		            				<i class="fa fa-caret-down" aria-hidden="true"></i>
			            			<span>Preperation</span>
		            			</div>
		            		</div>
		            		<div class="lq_block_content">
		            			<p>{!!$dialogs->vocabulary!!}</p>
		            		</div>
		            	</div>
		            @php } @endphp
	            	@php if (count($grammars) != 0) { @endphp
		            	<div class="lq_block lq_block_show lq_clicked">
		            		<div class="lq_block_click">
		            			<div class="lq_block_cl">
		            				<i class="fa fa-caret-down" aria-hidden="true"></i>
			            			<span>Grammar</span>
		            			</div>
		            		</div>
		            		<div class="lq_block_content">
		            			@foreach ($grammars as $grammar)
		            				<p>{!!$grammar->title!!}</p>
		            			@endforeach
		            		</div>
		            	</div>
	            	@php } @endphp
	            	<div class="lq_block lq_block_show lq_clicked lq_block_audio">
	            		<div class="lq_block_click">
	            			<div class="lq_block_cl">
	            				<i class="fa fa-caret-down" aria-hidden="true"></i>
		            			<span>Audio</span>
	            			</div>
	            		</div>
	            		<div class="lq_block_content">
	            			<audio controls class="m-b m-t">
								<source src="{!! asset('assets/audio/'.$dialogs->audio) !!}">
							</audio>
	            		</div>
	            	</div>	 
	            	<div class="lq_block lq_block_test">
	            		<div class="lq_block_click">
	            			<div class="lq_block_cl">
	            				<i class="fa fa-caret-right" aria-hidden="true"></i>
		            			<span>Test</span>
	            			</div>
	            		</div>
	            		<div class="lq_block_content">
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
	            		</div>
	            	</div>	
	            	<div class="lq_block">
	            		<div class="lq_block_click">
	            			<div class="lq_block_cl">
	            				<i class="fa fa-caret-right" aria-hidden="true"></i>
		            			<span>Dialog</span>
	            			</div>
	            		</div>
	            		<div class="lq_block_content">
	            			<p>{!! $dialogs->dialog !!}</p>	
	            		</div>
	            	</div>   
	            	<div class="listen_comment_fb">
	            	  	<div id="fb-root"></div>
						<script>
							(function(d, s, id) {
							var js, fjs = d.getElementsByTagName(s)[0];
							if (d.getElementById(id)) return;
							js = d.createElement(s); js.id = id;
							js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=1247438108670507";
							fjs.parentNode.insertBefore(js, fjs);
							}(document, 'script', 'facebook-jssdk'));
						</script>
						<div class="fb-comments" data-href="{{url('/listening/test/'.$get_id)}}" data-width="100%" data-numposts="5"></div>
					</div>
	            </div>
	        </div>
	    </div>
	    <div class="col-lg-4">
	    	<div class="sidebar_page_front">
	    		<div class="spf_title">
		    		<h3 class="text-center">Categories Listen</h3>
	    		</div>
	    		<div class="spf_content">
	    			<ul>
	    				@foreach ($cats as $new_cat)
	    					@if ($new_cat->title == $cat->title)
	            				<li><a href="{{url('/listening/dialogs/'.$new_cat->id)}}" class="cl_active">{{$new_cat->title}}</a></li>
	            			@else
	            				<li><a href="{{url('/listening/dialogs/'.$new_cat->id)}}">{{$new_cat->title}}</a></li>
	            			@endif
	            		@endforeach
	    			</ul>
	    		</div>
	    	</div>
	    </div>
	</div>
</div>
@endsection