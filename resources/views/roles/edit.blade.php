@extends('layouts.app')

@section('content')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
<div class="row">
	<div class="section">
		<div class="col m1 hide-on-med-and-down">
			@include('inc.sidebar')
		</div>
		<div class="col m11 s12">
			{{Breadcrumbs::render('editroles', $role)}}     
			<div class="row">
				<h3 class="flow-text"><i class="material-icons">mode_edit</i> Roles + Permissions</h3>
				<div class="divider"></div>
			</div>
			<div class="row">
				{!! Form::open(['action' => ['RolesController@update', $role->id], 'method' => 'PUT']) !!}
				<div class="card z-depth-2 hoverable">
					<div class="card-content">
						<h5 class="indigo-text">Assign Roles With Permissions</h5>
						<div class="input-field">
							<i class="material-icons prefix">assignment_ind</i>
							{{ Form::text('name',$role->name,['class' => 'validate', 'id' => 'role']) }}
							<label for="role">Role</label>
						</div>
						<div class="input-field">
							<h6 class="teal-text">Available Permissions</h6>
							@foreach ($permissions->chunk(8) as $chunk)	
							<div class="row">
							@foreach($chunk as $permission)
								<span class="column" style="width:20%;display: inline-block;">
								{{ Form::checkbox('permissions[]', $permission->id, (count($role->permissions->where('id', $permission->id)) ? true:null), ['class' => 'filled-in', 'id' => $permission->id]) }}
								
								<label for="{{ $permission->id }}">{{ ucfirst($permission->name) }}</label>
								</span> 
							@endforeach
							</div>
							@endforeach
						</div>
						<div class="input-field">
							<p class="center">{{ Form::submit('Assign', ['class' => 'btn waves-effect waves-light']) }}</p>
						</div>
					</div>
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@endsection