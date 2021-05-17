@extends('layouts.app')

@section('content')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
<div class="row">
    <div class="section">
    <div class="col m1 hide-on-med-and-down">
            @include('inc.sidebar')
        </div>
        <div class="col m11 s12">            
            {{Breadcrumbs::render('subcategories', $id)}}      
            <div class="row">
                <h5 class="flow-text"><i class="material-icons">class</i>Sub Categories : {{$cat_name}}
                    <button data-target="modal1" class="btn waves-effect waves-light modal-trigger right">Add New Sub Category</button>
                </h5>      
                <div class="divider"></div> 
            </div> 
            
            <div class="card z-depth-2">
            <div class="card-content">
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
                            <tr class="indigo lighten-2">
                                <td>
                                    <input type="checkbox" id="chk_{{ $subcategory->id }}" class="sub_chk"
                                        data-id="{{ $subcategory->id }}">
                                    <label for="chk_{{ $subcategory->id }}"></label>
                                </td>
                                <td class="white-text text-white"><strong>{{ $subcategory->name }}</strong></td>
                                <td>
                                {!! Form::open(['action' => ['CategoriesController@destroy', $subcategory->id], 'method' => 'DELETE', 'id' => 'form-delete-categories-' . $subcategory->id]) !!}                                    
                                    <!-- DELETE using link -->                                    
                                    <!-- <a class="accordion-toggle" id="accordion_{{$subcategory->id}}" data-toggle="collapse" data-parent="#accordion_{{$subcategory->id}}" href="#collapse_{{$subcategory->id}}"><i
                                                class="material-icons white-text text-white">expand_more</i></a> -->
                                    <a href="/categories/{{ $subcategory->id }}/edit"><i
                                                class="material-icons white-text text-white">mode_edit</i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <!-- add new item under sub category-->
                                    <a href="#modalAddSubItem"  class="addItem" data-id="{{$subcategory->id}}"><i class="material-icons white-text text-white">add</i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="" class="data-delete" data-form="categories-{{ $subcategory->id }}"><i class="material-icons white-text text-white">delete</i></a>
                                    
                                {!! Form::close() !!}
                                </td>
                            </tr>
                                @if(count($subcatitem) > 0)
                                    @foreach($subcatitem[$subcategory->id] as $item)
                                        @foreach($item as $i)
                                        <tr class="hide-table-padding">   
                                            <td>
                                                <input type="checkbox" id="chk_{{ $i->id }}" class="sub_chk"
                                                    data-id="{{ $i->id }}">
                                                <label for="chk_{{ $i->id }}"></label>
                                            </td> 
                                            <td>{{ucwords($i->name)}}</td>                                                                            
                                            <td>
                                            {!! Form::open(['action' => ['CategoriesController@destroy', $i->id], 'method' => 'DELETE', 'id' => 'form-delete-categories-' . $i->id]) !!}
                                            <a href="/categories/{{ $i->id }}/edit"><i
                                                    class="material-icons">mode_edit</i></a> &nbsp;&nbsp;
                                                <a href="" class="data-delete"
                                                data-form="categories-{{ $i->id }}"><i
                                                    class="material-icons">delete</i></a>    
                                            {!! Form::close() !!}
                                            </td>                               
                                        </tr>
                                        @endforeach
                                    @endforeach
                                @endif
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
<!-- Modal Structure -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <h5>Add Sub Category</h5>
        {!! Form::open(['action' => ['SubCategoryController@store', $id], 'method' => 'POST', 'class' => 'col s12']) !!}
        <div class="col s12 input-field">
            <i class="material-icons prefix">class</i>
            {{ Form::text('name', '', ['class' => 'validate', 'id' => 'name']) }}
            <label for="name">Category Name</label>
        </div>
        <!-- <div class="col s12 input-field">
            <i class="material-icons prefix">class</i>
            {{ Form::text('parent_cat', $id, ['class' => 'validate', 'id' => 'parent_cat']) }}
            <label for="parent_cat">Category Name</label>
        </div> -->        
    </div>        
    <div class="modal-footer">
        {{ Form::submit('submit', ['class' => 'btn']) }}
        {!! Form::close() !!}
    </div>
</div>

<div id="modalAddSubItem" class="modal">
    <div class="modal-content">
    <form id="addSubItemForm">
        <input type="hidden" name="subcatID" id="subcatID" value=""/>
        <h5>Add Sub Category Item</h5>
        <div class="col s12 input-field">
            <i class="material-icons prefix">class</i>
            {{ Form::text('name', '', ['class' => 'validate', 'id' => 'name']) }}
            <label for="name">Item Name</label>
        </div>            
    </div>    
    <div class="modal-footer">
        {{ Form::submit('submit', ['class' => 'btn']) }}
        {!! Form::close() !!}
    </div> 
    </form>   
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () { 
        $(".addItem").on('click', function(){
            var ID = $(this).data('id');//subcat id
            console.log(ID);
            $('#subcatID').val(ID);            
        });
        //submit form
        $('#addSubItemForm').on('submit',function(event){           
            event.preventDefault();            
            var parent_id = {!! json_encode($id) !!}; //parent id
            var data = $('#addSubItemForm').serialize()
            // $('#modalAddSubItem').modal('show');
            $.ajax({
                url:"{{ route('subcat.store.item') }}",
                method:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",                    
                    parent_id:parent_id,
                    data:data,
                },
                success:function(response){  
                    window.location=response.url;
                }
            });            
        }); 
    });
</script> 

