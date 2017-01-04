@foreach($items as $item)
	<li @if($item->title == 'Home' || $item->title == 'Video' || $level == 2) class="active" @endif>
		@if($item->hasChildren())
			@if($item->title =='Video')
				<a href="{!! $item->url() !!}">
					<i class="fa fa-youtube"></i>
					<span class="nav-label">{!! $item->title !!}</span>
					<span class="fa arrow"></span>
				</a>
			@elseif($item->title =='Graphs')
				<a href="{!! $item->url() !!}">
					<i class="fa fa-bar-chart-o"></i>
					<span class="nav-label">{!! $item->title !!}</span>
					<span class="fa arrow"></span>
				</a>
			@else
				<span class="nav-label">{!! $item->title !!}</span>
				<span class="fa arrow"></span>
			@endif
		@elseif($item->title == 'Create Cat')
			<a href="{!! $item->url() !!}">
				{!! $item->title !!}
				<span class="label label-primary pull-right">NEW</span>				
			</a>
		@else
			@if($item->title == 'Home')
				<a href="{!! $item->url() !!}">
					<i class="fa fa-th-large"></i>
					<span class="nav-label">{!! $item->title !!} </span>					
				</a>
			@elseif($item->title == 'Profile')
				<a href="{!! $item->url() !!}">
					<i class="fa fa-envelope"></i>
					<span class="nav-label">{!! $item->title !!} </span>					
				</a>
			@elseif($item->title == 'Layouts')
				<a href="{!! $item->url() !!}">
					<i class="fa fa-diamond"></i>
					<span class="nav-label">{!! $item->title !!} </span>					
				</a>
			@else 	<a href="{!! $item->url() !!}">{!! $item->title !!}</a>
			@endif
		@endif
		@if($item->hasChildren())
			<ul class="nav nav-second-level collapse">
			      @include('layouts\custom-menu-items', array('items' => $item->children(), 'level' => 2))
			</ul> 
		@endif
	</li>
@endforeach