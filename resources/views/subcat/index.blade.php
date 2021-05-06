@extends('layouts.app')

@section('content')
    <div class="row">
    <div class="col-sm-1">
        @include('inc.sidebar')
    </div>
    <div class="col-sm-11">
        <main>
        <div class="container-fluid">
            <br> 
            <div class="col-lg-12">
                <h3 class="flow-text"><i class="material-icons">class</i>Sub Categories : {{$cat_name}}
                    <button data-target="#modal1" data-toggle="modal" class="btn btn-info right">AddNew</button>
                </h3>       
            </div> 
            <br>
            <div class="divider"></div>
            <div class="card z-depth-2">
            <div class="card-content">
                {{-- <form action="{{ route('subcategoriesDeleteMulti') }}" method="post">
                    {{ csrf_field() }}
                    <button class="btn red waves-effect waves-light delete_all" type="submit">Delete All Selected</button> --}}
                <button class="btn red waves-effect waves-light delete_all"
                    data-url="{{ url('subcategoriesDeleteMulti') }}">Delete All Selected</button>
                <table class="responsive-table bordered centered highlight">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="master"><label for="master"></label></th>
                            <th>Sub Category Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($subcategories) > 0)
                            @foreach ($subcategories as $subcategory)
                                <tr id="tr_{{ $subcategory->id }}">
                                    <td>
                                        <input type="checkbox" id="chk_{{ $subcategory->id }}" class="sub_chk"
                                            data-id="{{ $subcategory->id }}">
                                        <label for="chk_{{ $subcategory->id }}"></label>
                                    </td>
                                    <td>{{ $subcategory->name }}</td>
                                    <td>
                                        <!-- DELETE using link -->
                                        {{--{!! Form::open(['action' => ['SubCategoryController@destroy', $subcategory->id], 'method' => 'POST', 'id' => 'form-delete-categories-' . $subcategory->id]) !!}--}}
                                        <a href="#" class="left"><i class="material-icons"></i></a>
                                        <a href="/categories/{{ $subcategory->id }}/edit" class="center"><i
                                                class="material-icons">mode_edit</i></a>
                                        <!-- <a href="/categories/{{ $subcategory->id }}/addSub" class="center"><i class="material-icons">mode_add</i></a>-->
                                        <a href="" class="right data-delete"
                                            data-form="categories-{{ $subcategory->id }}"><i
                                                class="material-icons">delete</i></a>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
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
        <main>        
    </div>
    </div>
    <!-- Modal -->
    <!-- Modal Structure -->
    <div id="modal1" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Sub Category</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                {!! Form::open(['action' => ['SubCategoryController@store', $id], 'method' => 'POST', 'class' => 'col s12']) !!}
                <div class="col s12 input-field">
                    <i class="material-icons prefix">class</i>
                    {{ Form::text('name', '', ['class' => 'validate', 'id' => 'name']) }}
                    <label for="name">Category Name</label>
                </div>
                <div class="col s12 input-field">
                    <i class="material-icons prefix">class</i>
                    {{ Form::text('parent_cat', $id, ['class' => 'validate', 'id' => 'parent_cat']) }}
                    <label for="parent_cat">Category Name</label>
                </div>
            </div>
            <div class="modal-footer">
                {{ Form::submit('submit', ['class' => 'btn']) }}
                {!! Form::close() !!}
            </div>
        </div>        
    </div>
@endsection

{{-- <script type="text/javascript">
    $(document).ready(function () {
        $('#master').on('click', function(e) {
         if($(this).is(':checked',true))  
         {
            $(".sub_chk").prop('checked', true);  
         } else {  
            $(".sub_chk").prop('checked',false);  
         }  
        });
        $('.delete_all').on('click', function(e) {
            var allVals = [];  
            $(".sub_chk:checked").each(function() {  
                allVals.push($(this).attr('data-id'));
            });  
            if(allVals.length <=0)  
            {  
                alert("Please select row.");  
            }  else {  
                var check = confirm("Are you sure you want to delete this row?");  
                if(check == true){  
                    var join_selected_values = allVals.join(","); 
                    $.ajax({
                        url: $(this).data('url'),
                        type: 'DELETE',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: 'ids='+join_selected_values,
                        success: function (data) {
                            if (data.success) {
                                $(".sub_chk:checked").each(function() {  
                                    $(this).parents("tr").remove();
                                });
                                alert(data.msg);
                                location.reload();
                            } 
                            else {
                                alert('Whoops Something went wrong!!');
                            }
                        },
                        error: function (data) {
                            alert(data.responseText);
                        }
                    });
                  $.each(allVals, function( index, value ) {
                      $('table tr').filter("[data-row-id='" + value + "']").remove();
                  });
                }  
            }  
        });
        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            onConfirm: function (event, element) {
                element.trigger('confirm');
            }
        });
        $(document).on('confirm', function (e) {
            var ele = e.target;
            e.preventDefault();
            $.ajax({
                url: ele.href,
                type: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    if (data['success']) {
                        $("#" + data['tr']).slideUp("slow");
                        alert(data['success']);
                    } else if (data['error']) {
                        alert(data['error']);
                    } else {
                        alert('Whoops Something went wrong!!');
                    }
                },
                error: function (data) {
                    alert(data.responseText);
                }
            });
            return false;
        });
    });
</script> --}}

