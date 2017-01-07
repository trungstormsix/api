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
	</div>
</div>
@endsection