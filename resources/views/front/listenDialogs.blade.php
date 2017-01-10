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
	                <div>
	                    <div class="feed-activity-list">
	                        @foreach($dialogs as $dialog)
	                        <div class="feed-element">                             
	                            <div class="media-body ">
	                                <small class="pull-right text-navy">5h ago</small>
	                                <h2>{{$dialog->title}}</h2>
	                                <div class="actions">
	                                    <a class="btn btn-xs btn-white"><i class="fa fa-thumbs-up"></i> Like </a>
	                                    <a class="btn btn-xs btn-white"><i class="fa fa-heart"></i> Love</a>
	                                    <a class="btn btn-xs btn-white" href="{{ url('listening/test/'.$dialog->id) }}"><i class="fa fa-heart"></i> Test</a>
	                                </div>
	                            </div>
	                        </div>                                                     
	                        @endforeach
	                    </div>
	                </div>
					{{ $dialogs->links() }}
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
	            				<li><a class="cl_active">{{$new_cat->title}}</a></li>
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