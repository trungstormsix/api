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
	        @foreach ($roles as $role)
				<tr>				
					<td > {{$role->id}} </td>
					<td > {{$role->name}} </td>
					<td > {{$role->display_name}} </td>
					<td > {{$role->description}} </td>
					<td > {{$role->created_at}} </td>
					<td > {{$role->updated_at}} </td>
					<td>
						<a href="{{ url('admin/user/role/edit/'. $role->id) }}" class="btn btn-info">Update</a>
						<a href="{{ url('admin/user/role/delete/' . $role->id) }}" class="btn btn-danger">Delete</a>
					</td>
				</tr>
			@endforeach
	        </tbody>
	    </table>

	</div>
</div>

@endsection