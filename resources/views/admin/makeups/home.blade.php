@extends('layouts.admin')

@section('content')

<div id="home_categories" >
	<div class="ibox-content">
		<a href="{{ url('admin/makeup/cat/create')}}" type="button" class="btn btn-primary btn-lg">Add new Categories</a>
	    <table class="table">
	        <thead>
	        <tr>
	            <th >Id</th>
				<th >Name</th>
 				<th >Title</th>
 				<th >Title Display</th>
				<th >Publish</th>
				<th >Published</th>
				<th >&nbsp;</th>
	        </tr>
	        </thead>
	        <tbody>
	        @foreach ($categories as $category)
				<tr>				
					<td > {{$category->id}} </td>
                                        <td ><a href='{{ url('admin/makeup/articles/'. $category->id) }}'>{{$category->title}}</a></td>
					<td > {{$category->title_display}} </td>
 					<td > {{$category->description}} </td>
 					<td > {{$category->published}} </td>
					<td style="width: 162px;">
						<a href="{{ url('admin/makeup/cat/edit/'. $category->id) }}" class="btn btn-info">Update</a>
						<a href="{{ url('admin/makeup/cat/delete/' . $category->id) }}" class="btn btn-danger">Delete</a>
					</td>
				</tr>
			@endforeach
	        </tbody>
	    </table>
		<div class="menu_pagination">{{$categories->links()}}</div>
	</div>
</div>

@endsection