@extends('layouts.app')

@section('content')
<div class="row">
    <div class="section">
        <div class="col m1 hide-on-med-and-down">
            @include('inc.sidebar')
        </div>
        <div class="col m11 s12">
            <div class="row">
                <h3 class="flow-text"><i class="material-icons">class</i> Categories
                    <button data-target="modal1" class="btn waves-effect waves-light modal-trigger right">Add
                        New</button>
                </h3>
                <div class="divider"></div>
            </div>
            <div class="card z-depth-2">
                <div class="card-content">
                    <button class="btn red waves-effect waves-light delete_all" data-url="{{ url('categoriesDeleteMulti') }}">Delete All Selected</button>
                    <table class="responsive-table bordered centered highlight">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="master"><label for="master"></label></th>
                                <th>Category Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($categories) > 0)
                            @foreach ($categories as $category)
                            <ul class="collapsible" data-collapsible="accordion">
                                <li>
                                    <tr id="tr_{{ $category->id }}">
                                        <td>
                                            <input type="checkbox" id="chk_{{ $category->id }}" class="sub_chk" data-id="{{ $category->id }}">
                                            <label for="chk_{{ $category->id }}"></label>
                                        </td>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            <!-- DELETE using link -->
                                            {!! Form::open(['action' => ['CategoriesController@destroy', $category->id], 'method' => 'POST', 'id' => 'form-delete-categories-' . $category->id]) !!}
                                            <a href="#" class="left"><i class="material-icons"></i></a>
                                            <a href="/categories/{{ $category->id }}/edit" class="center"><i class="material-icons">mode_edit</i></a>
                                            <a href="/categories/{{ $category->id }}/addSub" class="center"><i class="material-icons">mode_add</i></a>
                                            <a href="" class="right data-delete" data-form="categories-{{ $category->id }}"><i class="material-icons">delete</i></a>
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                    <div class="collapsible-body"><span class="teal-text">
                                            @foreach ($category->childs as $sub)
                                            <option value="{{ $sub->id }}">
                                                {{ $category->name }}-{{ $sub->name }}
                                            </option>
                                            @foreach ($sub->childs as $subsub)
                                            <option value="{{ $subsub->id }}" name="child_id">
                                                {{ $category->name }}-{{ $sub->name }}--{{ $subsub->name }}
                                            </option>
                                            @endforeach
                                            @endforeach
                                        </span></div>
                                </li>
                            </ul>
                            @endforeach

                            @else
                            <tr>
                                <td colspan="3">
                                    <h5 class="teal-text">No Category has been added</h5>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<!-- Modal Structure -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <h4>Add Category</h4>
        <div class="divider"></div>
        {!! Form::open(['action' => 'CategoriesController@store', 'method' => 'POST', 'class' => 'col s12']) !!}
        <div class="col s12 input-field">
            <i class="material-icons prefix">class</i>
            {{ Form::text('name', '', ['class' => 'validate', 'id' => 'category']) }}
            <label for="category">Category Name</label>
        </div>
        <div class="col s12 input-field">
            <i class="material-icons prefix">class</i>
            <select class="form-control" name="parent_id" id="parent_id">
                <option value="0">Select Category</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <label for="parent_id">Parent Name</label>
        </div>
        <div class="col s12 input-field">
            <i class="material-icons prefix">class</i>
            <select class="form-control" name="child_id" id="child_id">
                {{--<option value="0">Select Category</option>
                     @foreach ($category->childs as $sub)
                          <option value="{{ $sub->id }}">{{ $category->name }} - {{ $sub->name }}</option>
                {{-- @foreach ($sub->childs as $subsub)
                              <option value="{{ $subsub->id }}" name="child_id">
                {{ $category->name }}-{{ $sub->name }}--{{ $subsub->name }}</option>
                @endforeach
                @endforeach --}}
            </select>
            <label for="child_id">Child Name</label>
        </div>
        {{-- <div class="col s12 input-field">
                <i class="material-icons prefix">class</i>
                <select class="form-control" name="parent_id">
                    <option selected disabled>Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" name="parent_id">{{ $category->name }}</option>
        {{-- @foreach ($category->childs as $sub)
                        <option value="{{ $sub->id }}">{{ $category->name }}-{{ $sub->name }}</option>
        @foreach ($sub->childs as $subsub)
        <option value="{{ $subsub->id }}" name="child_id">
            {{ $category->name }}-{{ $sub->name }}--{{ $subsub->name }}
        </option>
        @endforeach
        @endforeach --}}
        {{-- @endforeach
                </select>
                <label for="parent_id"> sub Child Name</label>
            </div> --}}
    </div>

    <div class="modal-footer">
        {{ Form::submit('submit', ['class' => 'btn']) }}
        {!! Form::close() !!}
    </div>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type=text/javascript>
    $('#parent_id').change(function() {
        var parent_id = $(this).val();
        if (parent_id) {
            $.ajax({
                type: "GET",
                url: "{{url('getChild')}}?parent_id=" + parent_id,
                success: function(res) {
                    if (res) {
                        $("#child_id").empty();
                        $("#child_id").append('<option>Select State</option>');
                        $.each(res, function(key, value) {
                            $("#child_id").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#child_id").empty();
                    }
                }
            });
        } else {
            //$("#parent_id").empty();
            $("#child_id").empty();
        }
    });
    $('#child_id').on('change', function() {
        var child_id = $(this).val();
        if (child_id) {
            $.ajax({
                type: "GET",
                url: "{{url('getChild')}}?child_id=" + child_id,
                success: function(res) {
                    if (res) {
                        $("#city").empty();
                        $("#child_id").append('<option>Select City</option>');
                        $.each(res, function(key, value) {
                            $("#child_id").append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#child_id").empty();
                    }
                }
            });
        } else {
            $("#child_id").empty();
        }

    });
</script>