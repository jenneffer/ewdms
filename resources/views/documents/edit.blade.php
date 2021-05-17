@extends('layouts.app')

@section('content')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
<div class="row">
    <div class="section">
        <div class="col m1 hide-on-med-and-down">
            @include('inc.sidebar')
        </div>
        <div class="col m11 s12">
            @if(empty($parent_id)) {{Breadcrumbs::render('documentEdit', $doc)}}  
            @elseif(empty($child_id)) {{Breadcrumbs::render('documentEditSub',$parent_id, $doc)}}        
            @else {{Breadcrumbs::render('subcategoryitemdetails', $parent_id, $child_id, $category_id, $doc)}}  
            @endif
            <div class="row">
                <h5 class="flow-text"><i class="material-icons">mode_edit</i> Edit Document
                    <a href="{{ url()->previous() }}" class="btn waves-effect waves-light right tooltipped" data-position="left"
                        data-delay="50" data-tooltip="Go Back"><i class="material-icons">arrow_back</i> Back</a>
                </h5>
                <div class="divider"></div>
            </div>            
            <div class="row">
                <div class="col s12">
                    {!! Form::open(['action' => ['DocumentsController@update', $doc->id], 'method' => 'PATCH', 'enctype' => 'multipart/form-data', 'class' => 'col s12']) !!}
                    {{ csrf_field() }}
                    <div class="card">
                        <div class="card-content">
                            <div class="input-field">
                                <i class="material-icons prefix">folder</i>
                                {{ Form::text('name', $doc->name, ['class' => 'validate', 'id' => 'name']) }}
                                <label for="name">File Name</label>
                                @if ($errors->has('name'))
                                    <span class="red-text"><strong>{{ $errors->first('name') }}</strong></span>
                                @endif
                            </div>
                            <div class="input-field">
                                <i class="link-icons prefix">url</i>
                                {{ Form::url('url', $doc->url, ['class' => 'validate', 'id' => 'url']) }}
                                <label for="url">url</label>
                                @if ($errors->has('url'))
                                    <span class="red-text"><strong>{{ $errors->first('url') }}</strong></span>
                                @endif
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">description</i>
                                {{ Form::text('description', $doc->description, ['class' => 'validate', 'id' => 'description']) }}
                                <label for="description">Description</label>
                                @if ($errors->has('description'))
                                    <span class="red-text"><strong>{{ $errors->first('description') }}</strong></span>
                                @endif
                            </div>
                            <div class="input-field">
                                @if (is_null($doc->expires_at))
                                    {{ Form::checkbox('isExpire', 1, true, ['id' => 'isExpire']) }}
                                @else
                                    {{ Form::checkbox('isExpire', 1, false, ['id' => 'isExpire']) }}
                                @endif
                                <label for="isExpire">Does Not Expire</label>
                            </div>
                            <div class="input-field">
                                @if (is_null($doc->expires_at))
                                    {{ Form::text('expires_at', '', ['class' => 'datepicker', 'id' => 'expirePicker', 'disabled']) }}
                                @else
                                    {{ Form::text('expires_at', $doc->expires_at, ['class' => 'datepicker', 'id' => 'expirePicker']) }}
                                @endif
                                <label for="expirePicker">Expires At</label>
                            </div>
                            <!-- {{-- <div class="input-field">
                                <i class="material-icons prefix">class</i>
                                {{ Form::select('parent_id',$parent_name,$categories_name,['id' => 'parent_id']) }}
                                <label for="parent_id">Category (Optional)</label>
                                @if ($errors->has('parent_id'))
                                    <span class="red-text"><strong>{{ $errors->first('parent_id') }}</strong></span>
                                @endif
                                </div> --}} -->
                            <div class="row">
                                <div class="input-field col s4">
                                    <i class="material-icons prefix">class</i>
                                    <select name="parent_id">
                                        <option value="" default>Select Category</option>
                                        @foreach ($p_cat_name as $key => $value)
                                            @if ($cat->parent_id == 0)
                                                <option value="{{ $key }}"
                                                    {{ $key == $doc->category_id ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @else
                                            <option value="{{ $key }}">{{ $value }}</option>
                                            @endif
                                        @endforeach
                                        <label for="title">Category (Optional)</label>
                                    </select>
                                </div>
                                <div class="input-field col s4">
                                    <i class="material-icons prefix">class</i>
                                    <select name="subcat_id">
                                        <option value="" default>Select Sub Category</option>
                                        @foreach ($sub_cat_name as $key => $value)
                                            @if ($cat->child_id == 0)
                                                <option value="{{ $key }}"
                                                    {{ $key == $doc->category_id ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @else
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <label for="title">Sub Category</label>
                                </div>
                                <div class="input-field col s4">
                                    <i class="material-icons prefix">class</i>
                                    <select name="child_id">
                                        <option value="" default>Select Child Category</option>
                                        @foreach ($child_list as $key => $value)
                                            @if ((!$parent_id == 0) || (!$cat->child_id == 0))
                                                <option value="{{ $key }}"
                                                    {{ $key == $doc->category_id ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @else
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <label for="title">Sub Category Item</label>
                                </div>
                            </div> 
                            <div class="input-field">
                                <p class="center">
                                    {{ Form::submit('Save Changes', ['class' => 'btn waves-effect waves-light']) }}
                                </p>
                            </div>                           
                        </div>                        
                    </div>                    
                    {!! Form::close() !!}
                </div>
                <!-- <div class="col m4 hide-on-med-and-down">
                    <div class="card-panel teal">
                        <h4>Notice</h4>
                        <p>
                        <ul>
                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
                                dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                                Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt
                                mollit anim id est laborum.</li>
                        </ul>
                        </p>
                    </div>
                </div> -->
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
                    url: '../../getSubCat/' + stateID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="subcat_id"]').empty();
                        $('select[name="subcat_id"]').append(
                            '<option value="">Select Sub Category</option>');
                        $.each(data, function(key, value) {
                            $('select[name="subcat_id"]').append('<option value="' +
                                key + '">' + value + '</option>');

                        });
                        $('select[name="subcat_id"]').material_select();
                    }
                });
            } else {
                $('select[name="subcat_id"]').empty();
            }
        });
    });

    $(document).ready(function() {
        $('select[name="subcat_id"]').on('change', function() {
            var stateID = $(this).val();
            if (stateID) {
                $.ajax({
                    url: '../../getChildCat/' + stateID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="child_id"]').empty();
                        $('select[name="child_id"]').append(
                            '<option value="">Select Sub Category</option>');
                        $.each(data, function(key, value) {
                            $('select[name="child_id"]').append('<option value="' +
                                key + '">' + value + '</option>');

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
