@extends('layouts.admin')

@section('content')

<div id="create_roles" >
	<div class="ibox-content">
		{!! Form::open(['url' => 'admin/user/role/save']) !!}

		{!! Form::label('name','Name:') !!}
		{!! Form::text('name') !!} <br />

		{!! Form::label('display_name','Display Name:') !!}
		{!! Form::text('display_name') !!} <br />
 
		{!! Form::label('description','Description:') !!}
		{!! Form::text('description') !!} </br>
 
		{!! Form::submit('Add new roles')!!}

		{!! Form::close() !!}
	</div>
</div>

@endsection