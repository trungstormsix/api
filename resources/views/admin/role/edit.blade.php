@extends('layouts.admin')

@section('content')

<div id="edit_roles" >
	<div class="ibox-content">
		{!! Form::model($roles,[ 'method' => 'PATCH', 'action' => ['Admin\RolesController@update', $roles->id] ]) !!}

		{!! Form::label('name','Name:') !!}
		{!! Form::text('name') !!} <br />

		{!! Form::label('dis_name','Display Name:') !!}
		{!! Form::text('display_name') !!} <br />
 
		{!! Form::label('description','Description:') !!}
		{!! Form::text('description') !!} </br>
 
		{!! Form::submit('Update roles')!!}

		{!! Form::close() !!}
	</div>
</div>

@endsection