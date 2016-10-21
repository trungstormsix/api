@extends('layouts.admin')

@section('content')

<div id="home_permissionss" >
	<div class="ibox-content">
		<a href="{{ url('admin/user/permission/create')}}" type="button" class="btn btn-primary btn-lg">Add new Permission</a>
	    <table class="table">
	        <thead>
	        <tr>
	            <th >Id</th>
				<th >Name</th>
				<th >Display name</th>
				<th >Description</th>
				<th >Created_at</th>
				<th >Updated_at</th>
				<th >Function</th>
	        </tr>
	        </thead>
	        <tbody>
	        @foreach ($permissions as $permission)
				<tr>				
					<td > {{$permission->id}} </td>
					<td > {{$permission->name}} </td>
					<td > {{$permission->display_name}} </td>
					<td > {{$permission->description}} </td>
					<td > {{$permission->created_at}} </td>
					<td > {{$permission->updated_at}} </td>
					<td>
						<a href="{{ url('admin/user/permission/edit/' . $permission->id) }}" class="btn btn-info">Update</a>
						<a href="{{ url('admin/user/permission/delete/' . $permission->id) }}" class="btn btn-danger">Delete</a>
					</td>
				</tr>
			@endforeach
	        </tbody>
	    </table>

	</div>
</div>

@endsection