@extends('layouts.admin')

@section('content')

<div id="home_roles" >
	<div class="ibox-content">
		<a href="{{ url('admin/user/role/create')}}" type="button" class="btn btn-primary btn-lg">Add new Roles</a>
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
	        @foreach (\App\Http\Controllers\Admin\RolesController::getRoles() as $roles)
				<tr>				
					<td > {{$roles->id}} </td>
					<td > {{$roles->name}} </td>
					<td > {{$roles->display_name}} </td>
					<td > {{$roles->description}} </td>
					<td > {{$roles->created_at}} </td>
					<td > {{$roles->updated_at}} </td>
					<td>
						<a href="{{ url('admin/user/role/edit/'. $roles->id) }}" class="btn btn-info">Update</a>
						<a href="{{ url('admin/user/role/delete/' . $roles->id) }}" class="btn btn-danger">Delete</a>
					</td>
				</tr>
			@endforeach
	        </tbody>
	    </table>

	</div>
</div>

@endsection