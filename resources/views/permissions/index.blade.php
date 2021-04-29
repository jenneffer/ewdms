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
                        <button class="btn red waves-effect waves-light delete_all"
                            data-url="{{ url('categoriesDeleteMulti') }}">Delete All Selected</button>
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
                                        <tr id="tr_{{ $category->id }}">
                                            <td>
                                                <input type="checkbox" id="chk_{{ $category->id }}" class="sub_chk"
                                                    data-id="{{ $category->id }}">
                                                <label for="chk_{{ $category->id }}"></label>
                                            </td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <!-- DELETE using link -->
                                                {!! Form::open(['action' => ['CategoriesController@destroy', $category->id], 'method' => 'DELETE', 'id' => 'form-delete-categories-' . $category->id]) !!}
                                                <a href="#" class="left"><i class="material-icons"></i></a>
                                                <a href="/categories/{{ $category->id }}/edit" class="center"><i
                                                        class="material-icons">mode_edit</i></a>
                                                <a href="/categories/{{ $category->id }}/addSub" class="center"><i
                                                        class="material-icons">mode_add</i></a>
                                                <a href="" class="right data-delete"
                                                    data-form="categories-{{ $category->id }}"><i
                                                        class="material-icons">delete</i></a>
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                        {{-- <div class="collapsible-body"><span class="teal-text">
                                                @foreach ($category->childs as $sub)
                                                    <option value="{{ $sub->id }}">
                                                        {{ $category->name }}-{{ $sub->name }}</option>
                                                    @foreach ($sub->childs as $subsub)
                                                        <option value="{{ $subsub->id }}" name="child_id">
                                                            {{ $category->name }}-{{ $sub->name }}--{{ $subsub->name }}
                                                        </option>
                                                    @endforeach
                                                @endforeach
                                            </span>
                                        </div> --}}
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
                <select name="parent_id" class="form-control">
                    <option value="">Select Category</option>
                    @foreach ($categories_name as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                    <label for="title">Category</label>
                </select>
            </div>
            <div class="col s12 input-field">
                <i class="material-icons prefix">class</i>
                <select name="child_id" class="form-control"></select>
                <label for="title">Sub Category</label>
            </div>
        </div>

        <div class="modal-footer">
            {{ Form::submit('submit', ['class' => 'btn']) }}
            {!! Form::close() !!}
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
                    url: 'getSubCat/' + stateID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="child_id"]').empty();
                        $('select[name="child_id"]').append('<option value="">Select Sub Category</option>');
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
