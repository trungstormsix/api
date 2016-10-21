@extends('layouts.admin')

@section('content')

<div id="home_permissionss" >
	<div class="ibox-content">
		<a href="{{ url('/permissions/create')}}" type="button" class="btn btn-primary btn-lg">Add new Permission</a>
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
	        @foreach (\App\Http\Controllers\Admin\PermissionsController::getPermissions() as $permissions)
				<tr>				
					<td > {{$permissions->id}} </td>
					<td > {{$permissions->name}} </td>
					<td > {{$permissions->display_name}} </td>
					<td > {{$permissions->description}} </td>
					<td > {{$permissions->created_at}} </td>
					<td > {{$permissions->updated_at}} </td>
					<td>
						<a href="{{ url('/permissions/' . $permissions->id . '/edit') }}" class="btn btn-info">Update</a>
						<a href="{{ url('permissions/delete/' . $permissions->id) }}" class="btn btn-danger">Delete</a>
					</td>
				</tr>
			@endforeach
	        </tbody>
	    </table>

	</div>
</div>

@endsection