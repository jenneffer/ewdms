@extends('layouts.app')

@section('content')
<div class="row">
  <div class="section">
    <div class="col m1 hide-on-med-and-down">
      @include('inc.sidebar')
    </div>
    <div class="col m11 s12">
      <div class="row">
        <h3 class="flow-text"><i class="material-icons">mode_edit</i> Edit Permission
          <button data-target="modal1" class="btn waves-effect waves-light modal-trigger right">Add New</button>
        </h3>
        <div class="divider"></div>
      </div>
      {!! Form::open(['action' => ['PermissionsController@update',$permissions->id], 'method' => 'PATCH', 'class' => 'col m12']) !!}
      <div class="card z-depth-2">
        <div class="card-content">
          <div class="row">
            <div class="col m6 input-field">
              <i class="material-icons prefix">class</i>
              {{ Form::text('name', $permissions->name, ['class' => 'validate', 'id' => 'permission_name']) }}
              <label for="permission_name">Permission Title</label>
            </div>      
          </div>
          <div class="row">
            <div class="col m6 input-field">
              <i class="material-icons prefix">class</i>
              {{ Form::text('guard_name', $permissions->guard_name, ['class' => 'validate', 'id' => 'guard_name']) }}
              <label for="guard_name">Guard Name</label>
            </div>      
          </div>
          <div class="row">
            <div class="col m6 input-field">
              {{ Form::submit('Save Changes', ['class' => 'btn waves-effect waves-light']) }}
            </div>
          </div>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>

<!-- Modal Structure Add Permissions -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <h4>Add Permission</h4>
        <div class="divider"></div>
        {!! Form::open(['action' => 'PermissionsController@store', 'method' => 'POST', 'class' => 'col s12']) !!}
        <div class="col s12 input-field">
            <i class="material-icons prefix">class</i>
            {{ Form::text('name', '', ['class' => 'validate', 'id' => 'permission_name']) }}
            <label for="permission_name">Permission Title</label>
        </div>  
        <div class="col s12 input-field">
            <i class="material-icons prefix">class</i>
            {{ Form::text('guard_name', '', ['class' => 'validate', 'id' => 'guard_name']) }}
            <label for="guard_name">Guard Name</label>
        </div>            
    </div>

    <div class="modal-footer">
        {{ Form::submit('submit', ['class' => 'btn']) }}
        {!! Form::close() !!}
    </div>
</div>
@endsection
