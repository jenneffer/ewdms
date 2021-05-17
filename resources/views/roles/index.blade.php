@extends('layouts.app')
@section('content')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
<div class="row">
  <div class="section">
  <div class="col m1 hide-on-med-and-down">
    @include('inc.sidebar')
  </div>
  <div class="col m11 s12">
  {{Breadcrumbs::render('roles')}}     
      <div class="row">
        <h5 class="flow-text"><i class="material-icons">assignment_ind</i> Roles &amp; Permissions</h5>        
        <div class="divider"></div>
      </div>
      <div class="col m11 s12">
        <div class="card z-depth-2 hoverable">
            <div class="card-content">
            <h5 class="indigo-text">Roles + Permissions</h5>
            <a class="btn waves-effect waves-light modal-trigger right" href="roles/create">Add Role</a>
              <table class="responsive-table striped">
                <thead>
                  <tr>
                      <th>Role</th>
                      <th>Permissions</th>
                      <th></th>
                  </tr>
                </thead>

                <tbody>
                @if(count($roles) > 0)
                  @foreach($roles as $r)
                  <tr>
                    <td>{{ $r->name }}</td>
                    <td>{{ $r->permissions()->pluck('name')->implode(' ') }}</td>
                    <td>
                      @if(auth()->user()->hasRole('Admin'))
                      <a href="roles/{{ $r->id }}/edit"><i class="material-icons">mode_edit</i></a>
                      @else
                        @if($r->name !='Admin')
                          <a href="roles/{{ $r->id }}/edit"><i class="material-icons">mode_edit</i></a>
                        @endif
                      @endif
                    </td>
                  </tr>
                  @endforeach
                @endif
                </tbody>
              </table>
            </div>
          </div>
      </div>
      <!-- ====================== -->
      <div class="col m11 s12">
        <div class="card z-depth-2 hoverable">
          <div class="card-content">
            <h5 class="indigo-text">Permissions</h5>
            <button data-target="modal1" class="btn waves-effect waves-light modal-trigger right">Add Permission</button>
            <table class="striped">
              <thead>
                <tr>
                    <th>ID.</th>
                    <th>Permission</th>
                    <th>Action</th>
                </tr>
              </thead>

              <tbody>
              @if(count($permissions) > 0)
                @foreach($permissions as $key => $permission)
                <tr>
                  <td>{{ $key }}</td>
                  <td>{{ $permission }}</td>
                  <td>
                    @if(!in_array($key,[1,2,3,4,5,6,7,8]))
                    {!! Form::open(['action' => ['PermissionsController@destroy', $key], 'method' => 'DELETE', 'id' => 'form-delete-permissions-' . $key]) !!}
                    <a href="permissions/{{ $key }}/edit"><i class="material-icons">mode_edit</i></a>                      
                    <a href="" class="data-delete" data-form="permissions-{{ $key }}"><i class="material-icons">delete</i></a>
                    {!! Form::close() !!}
                    @endif
                  </td>
                  
                </tr>
                @endforeach
              @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  </div>
</div> 


<!-- Modal Structure Add Permissions -->
<div id="modal1" class="modal">
  <div class="modal-content">
    <h5>Add Permission</h5>
    {!! Form::open(['action' => 'PermissionsController@store', 'method' => 'POST', 'class' => 'col s12']) !!}
    <div class="col s12 input-field">
        <i class="material-icons prefix">class</i>
        {{ Form::text('name', '', ['class' => 'validate', 'id' => 'permission_name']) }}
        <label for="permission_name">Permission Title</label>
    </div>  
    <div class="col s12 input-field">
        <i class="material-icons prefix">class</i>
        <select name="parent_id">
              <option value="" default>Select Category</option>
              @foreach ($category as $key => $value)
                  <option value="{{ $key }}">{{ $value }}</option>
              @endforeach
              <label for="title">Category</label>
          </select>
    </div>
    <div class="col s12 input-field">
        <i class="material-icons prefix">class</i>
        <select name="child_id"></select>
        <label for="title">Sub Category</label>
    </div>
    <div class="col s12 input-field">
        <i class="material-icons prefix">class</i>
        <select name="sub_child_id"></select>
        <label for="title">Sub Category Item</label>
    </div>  
    <div class="modal-footer ">
      {{ Form::submit('submit', ['class' => 'btn center']) }}
      {!! Form::close() !!}
    </div>         
  </div>  
</div>

@endsection
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('select[name="parent_id"]').on('change', function() {
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    url: '../getSubCat/' + stateID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="child_id"]').empty();
                        $('select[name="child_id"]').append('<option value="">Select Sub Category</option>');
                        $.each(data, function(key, value) {
                            $('select[name="child_id"]').append('<option value="' +
                                key + '" >' + value + '</option>');

                        });
                        $('select[name="child_id"]').material_select();
                    }
                });
            } else {
                $('select[name="child_id"]').empty();
            }
        });

        $('select[name="child_id"]').on('change', function() {
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    url: '../getChildCat/' + stateID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                      // console.log(data);
                        $('select[name="sub_child_id"]').empty();
                        $('select[name="sub_child_id"]').append('<option value="">Select Sub Category Item</option>');
                        $.each(data, function(key, value) {
                            $('select[name="sub_child_id"]').append('<option value="' +
                                key + '" >' + value + '</option>');

                        });
                        $('select[name="sub_child_id"]').material_select();
                    }
                });
            } else {
                $('select[name="sub_child_id"]').empty();
            }
        });
    });

</script>