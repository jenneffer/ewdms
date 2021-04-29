@extends('layouts.app')

@section('content')
<div class="row">
  <div class="section">
    <div class="col m1 hide-on-med-and-down">
      @include('inc.sidebar')
    </div>
    <div class="col m11 s12">
      <div class="row">
        <h3 class="flow-text"><i class="material-icons">folder</i> Upload Document
          <a href="/documents/create" class="btn waves-effect waves-light right tooltipped" data-position="left" data-delay="50" data-tooltip="Upload New Document"><i class="material-icons">file_upload</i> New</a>
        </h3>
        <div class="divider"></div>
      </div>
      <div class="row">
        <div class="col m8 s12">
          {!! Form::open(['action' => 'DocumentsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'col s12']) !!}
            {{ csrf_field() }}
          <div class="card hoverable">
            <div class="card-content">
              <div class="input-field">
                <i class="material-icons prefix">folder</i>
                {{ Form::text('name','',['class' => 'validate', 'id' => 'name']) }}
                <label for="name">File Name</label>
                @if ($errors->has('name'))
                  <span class="red-text"><strong>{{ $errors->first('name') }}</strong></span>
                @endif
              </div>
              <br>
              <div class="input-field">
                <i class="link-icons prefix">url</i>
                {{ Form::url('url','',['class' => 'validate', 'id' => 'url']) }}
                <label for="url">Url</label>
                @if ($errors->has('url'))
                  <span class="red-text"><strong>{{ $errors->first('url') }}</strong></span>
                @endif
              </div>
              <br>
              <div class="input-field">
                <i class="material-icons prefix">description</i>
                {{ Form::text('description','',['class' => 'validate', 'id' => 'description']) }}
                <label for="description">Description</label>
                @if ($errors->has('description'))
                  <span class="red-text"><strong>{{ $errors->first('description') }}</strong></span>
                @endif
              </div>
              <br>
              <div class="input-field">
                <!-- <input type="checkbox" id="isExpire" name="isExpire" checked/> -->
                {{ Form::checkbox('isExpire',1,true,['id' => 'isExpire']) }}
                <label for="isExpire">Does Not Expire</label>
              </div>
              <br>
              <div class="input-field">
                <!-- <input type="text" class="datepicker" name="expires_at" id="expirePicker" disabled> -->
                {{ Form::text('expires_at', '',['class' => 'datepicker', 'id' => 'expirePicker', 'disabled']) }}
                <label for="expirePicker">Expires At</label>
              </div>
              <br>
              <!-- {{-- <div class="input-field">
                <i class="material-icons prefix">class</i>
                {{ Form::select('category_id[]',$categories,null,['multiple' => 'multiple', 'id' => 'category', 'placeholder' => 'Choose Category']) }}

                <label for="category">Category (Optional)</label>
                @if ($errors->has('category'))
                  <span class="red-text"><strong>{{ $errors->first('category') }}</strong></span>
                @endif
              </div> --}} -->

              <div class="input-field">
                <i class="material-icons prefix">class</i>
                <select name="parent_id" class="form-control">
                    <option value="" default>Select Category</option>
                    @foreach ($categories_name as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                    <label for="title">Category (Optional)</label>
                </select>
            </div>
            <div class="input-field">
                <i class="material-icons prefix">class</i>
                <select name="child_id" class="form-control"></select>
                <label for="title">Sub Category</label>
            </div>
              <br>
              {{--<div class="file-field input-field">
                <div class="btn white">
                  <span class="black-text">Choose File (Max: 50MB)</span>
                  {{ Form::file('file') }}
                  @if ($errors->has('file'))
                    <span class="red-text"><strong>{{ $errors->first('file') }}</strong></span>
                  @endif
                </div>
                <div class="file-path-wrapper">
                  <input class="file-path validate" type="text">
                </div>
              </div>--}}
              <br>
              <div class="input-field">
                <p class="center">
                  {{ Form::submit('Save',['class' => 'btn-large waves-effect waves-light']) }}
                </p>
              </div>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
        <div class="col m4 hide-on-med-and-down">
          <div class="card-panel teal">
            <h4>Notice</h4>
            <p>
              <ul>
                <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>
                <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</li>
              </ul>
            </p>
          </div>
        </div>
      </div>
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
    });

</script>