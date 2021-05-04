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
            <div class="input-field">
                <i class="material-icons prefix">class</i>
                <select name="parent_id" class="form-control">
                    <option value="" default>Select Category</option>
                    @foreach ($p_cat_name as $key => $value)
                        @if ($cat->parent_id == 0)
                            <option value="{{ $key }}" {{ $key == $permissions->category_id ? 'selected' : '' }}>{{ $value }}</option>                       
                        @else
                          <option value="{{ $key }}" {{$key == $cat->parent_id ? 'selected' : ''}}>{{ $value }}</option> 
                        @endif
                    @endforeach
                </select>
                <label for="title">Category</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field">
                <i class="material-icons prefix">class</i>
                <select name="subcat_id" class="form-control">
                    <option value="" default>Select Sub Category</option>
                    @foreach ($sub_cat_name as $key => $value)
                        @if ($cat->child_id == 0)
                            <option value="{{ $key }}"{{ $key == $permissions->category_id ? 'selected' : '' }}>{{ $value }}</option>
                        @else
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                </select>
                <label for="title">Sub Category</label>
            </div>
          </div>
          <!-- <div class="row">
            <div class="input-field">
                <i class="material-icons prefix">class</i>
                <select name="child_id" class="form-control">
                    <option value="" default>Select Child Category</option>
                    @foreach ($child_list as $key => $value)
                        @if ((!$parent_id == 0) || (!$cat->child_id == 0))
                            <option value="{{ $key }}"
                                {{ $key == $permissions->category_id ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @else
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                </select>
                <label for="title">Sub Category</label>
            </div>
          </div> -->
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
