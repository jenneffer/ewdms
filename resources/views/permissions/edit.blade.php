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
    {{Breadcrumbs::render('editpermission', $permissions)}}   
      <div class="col-sm-12">
        <h3 class="flow-text"><i class="material-icons">mode_edit</i> Edit Permission
          <button data-target="#modal1" data-toggle="modal" class="btn waves-effect waves-light modal-trigger right">Add New</button>
        </h3>        
      </div>
      <div class="divider"></div>
      {!! Form::open(['action' => ['PermissionsController@update',$permissions->id], 'method' => 'PATCH', 'class' => 'col m12']) !!}
      <div class="card z-depth-2">
        <div class="card-content">
          <div class="col-sm-12">
            <div class="input-field">
              <i class="material-icons prefix">class</i>
              {{ Form::text('name', $permissions->name, ['class' => 'validate', 'id' => 'permission_name']) }}
              <label for="permission_name">Permission Title</label>
            </div>      
          </div>          
          <div class="col-sm-12">
            <div class="input-field">
                <i class="material-icons prefix">class</i>
                <select name="parent_id">
                    <option value="" default>Select Category</option>
                    @if(!empty($p_cat_name))
                    @foreach ($p_cat_name as $key => $value)
                        @if ($cat->parent_id == 0)
                            <option value="{{ $key }}" {{ $key == $permissions->category_id ? 'selected' : '' }}>{{ $value }}</option>                       
                        @else
                          <option value="{{ $key }}" {{$key == $cat->parent_id ? 'selected' : ''}}>{{ $value }}</option> 
                        @endif
                    @endforeach
                    @endif
                </select>
                <label for="title">Category</label>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="input-field">
                <i class="material-icons prefix">class</i>
                <select name="subcat_id">
                    <option value="" default>Select Sub Category</option>
                    @if(!empty($sub_cat_name))
                    @foreach ($sub_cat_name as $key => $value)
                        @if ($cat->child_id == 0)
                            <option value="{{ $key }}"{{ $key == $permissions->category_id ? 'selected' : '' }}>{{ $value }}</option>
                        @else
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    @endif
                </select>
                <label for="title">Sub Category</label>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="input-field">
                <i class="material-icons prefix">class</i>
                <select name="child_id">
                    <option value="" default>Select Child Category</option>
                    @if(!empty($child_list))
                    @foreach ($child_list as $key => $value)
                        @if ((!$parent_id == 0) || (!$cat->child_id == 0))
                            <option value="{{ $key }}"
                                {{ $key == $permissions->category_id ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @else
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                    @endforeach
                    @endif
                </select>
                <label for="title">Sub Category Item</label>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="input-field center">
              {{ Form::submit('Save Changes', ['class' => 'btn waves-effect waves-light']) }}
            </div>
          </div>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
    </main>
  </div>
</div>

<!-- Modal Structure Add Permissions -->
<div id="modal1" class="modal">
  <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Permission</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
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
                  @if(!empty($p_cat_name))
                  @foreach ($p_cat_name as $key => $value)
                      <option value="{{ $key }}">{{ $value }}</option>
                  @endforeach
                  @endif
                  <label for="title">Category</label>
              </select>
        </div>
        <div class="col s12 input-field">
            <i class="material-icons prefix">class</i>
            <select name="child_id"></select>
            <label for="title">Sub Category</label>
        </div>     
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
            console.log(stateID);
            if (stateID) {
                $.ajax({
                    url: '../../getSubCat/' + stateID,
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
    });

</script>