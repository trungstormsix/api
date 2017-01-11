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
	        	<div class="list_listen_lesson">
	        		<div class="lll_content">
		        		@foreach($dialogs as $dialog)
		        			<div class="lll_item">
		        				<span class="lll_item_title">
		        					<a href="{{ url('listening/test/'.$dialog->id) }}">{{$dialog->title}}</a>
		        				</span>
		        				@php 	$dialogs_note = json_decode($dialog->note); @endphp		        				
		        				<div class="lll_item_content">
		        					@if ($dialogs_note)
				        				@foreach ($dialogs_note as $note)
				        					<p><b>{{$note}}</b></p>
				        				@endforeach
				        			@endif
		        				</div>		        				
		        			</div>
	        			@endforeach
	        			{{ $dialogs->links() }}
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