@extends('layouts.admin')

@section('content')

<div id="home_roles" >
	<div class="ibox-content">
		<a href="{{ url('admin/users/create')}}" type="button" class="btn btn-primary btn-lg">Add new User</a>
	    <table class="table">
	        <thead>
	        <tr>
	            <th >Id</th>
				<th >Name</th>
				<th >Display name</th>
				<th >Email</th>
				<th >Created_at</th>
				<th >Updated_at</th>
				<th >Function</th>
	        </tr>
	        </thead>
	        <tbody>
	        @foreach ($users as $user)
				<tr>				
					<td > {{$user->id}} </td>
					<td > {{$user->username}} </td>
					<td > {{$user->email}} </td>
 					<td > {{$user->created_at}} </td>
					<td > {{$user->updated_at}} </td>
					<td>
						<a href="{{ url('admin/users/edit/'. $user->id) }}" class="btn btn-info">Update</a>
						<a href="{{ url('admin/users/delete/' . $user->id) }}" class="btn btn-danger">Delete</a>
					</td>
				</tr>
			@endforeach
	        </tbody>
	    </table>

	</div>
</div>

@endsection