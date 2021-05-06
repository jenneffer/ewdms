@extends('layouts.app')

@section('content')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
<div class="row">
	<div class="col-sm-1">
		  @include('inc.sidebar')
	</div>
	<div class="col-sm-11">
		<main>
			<div class="container-fluid">
			<br>
			{{Breadcrumbs::render('addroles')}}     
				<div class="col-sm-12">			
					<h3 class="flow-text"><i class="material-icons">mode_edit</i> Roles + Permissions</h3>
					<div class="divider"></div>
				</div>
				<div class="col-sm-12">
					{!! Form::open(['action' => 'RolesController@store', 'method' => 'POST']) !!}
					<div class="card z-depth-2 hoverable">
						<div class="card-content">
							<h5 class="indigo-text">Add Roles With Permissions</h5>
							<br>
							<div class="input-field col-sm-12">
								<i class="material-icons prefix">assignment_ind</i>
								{{ Form::text('name','',['class' => 'validate', 'id' => 'role']) }}
								<label for="role">Role</label>
							</div>
							<br>
							<div class="input-field col-sm-12">
								<h6 class="teal-text">Available Permissions</h6>
								<br>	
								<div class="row">
								@foreach($permissions as $permission)								
									<div class="col-sm-3">
										{{ Form::checkbox('permissions[]', $permission->id, '', ['class' => 'filled-in', 'id' =>  $permission->id]) }}
										<label for="{{ $permission->id }}">{{ ucfirst($permission->name) }}</label>
									</div>							
								@endforeach
								<div>
							</div>
							<br>
							<div class="input-field">
								<p class="center">{{ Form::submit('Assign', ['class' => 'btn waves-effect waves-light']) }}</p>
							</div>													
						</div>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</main>
	</div>
</div>
@endsection