@extends('layouts.app')

@section('content')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
<div class="row">
  <div class="section">
  <div class="col m1 hide-on-med-and-down">
    @include('inc.sidebar')
    </div>
    <div class="col m11 s12">        
        {{Breadcrumbs::render('editcategories', $category)}}      
        <div class="row">
            <h5 class="flow-text"><i class="material-icons">class</i> Edit Categories               
                <button data-target="modal1"  class="btn waves-effect waves-light modal-trigger right">Add New</button>
            </h5>    
            <div class="divider"></div>  
        </div>
        
        {!! Form::open(['action' => ['CategoriesController@update',$category->id], 'method' => 'PATCH', 'class' => 'col m12']) !!}
        <div class="card z-depth-2">
          <div class="card-content">
            <div class="row">
              <div class="col m8 input-field">
                <i class="material-icons prefix">class</i>
                {{ Form::text('categoryName', $category->name, ['class' => 'validate', 'id' => 'category']) }}
                <label for="category">Category Name</label>
              </div>      
            </div>
            <div class="row center">
              <div class="col m2 input-field">
                <a href="{{ url()->previous() }}" class="btn"><i class="material-icons left">chevron_left</i>Back</a>
              </div>
              <div class="col m4 input-field">              
                {{ Form::submit('Save Changes', ['class' => 'btn waves-effect waves-light']) }}
              </div>
            </div>              
          </div>
        </div>
    </div>
    </div>
</div>

<!-- Modal -->
<!-- Modal Structure -->
<div id="modal1" class="modal">
  <div class="modal-content">
    <h5 class="modal-title">Add Category</h5>
    {!! Form::open(['action' => 'CategoriesController@store', 'method' => 'POST', 'class' => 'col s12']) !!}
    <div class="col s12 input-field">
      <i class="material-icons prefix">class</i>
      {{ Form::text('name','',['class' => 'validate', 'id' => 'category']) }}
      <label for="category">Category Name</label>
    </div>   
    <div class="modal-footer">
      {{ Form::submit('submit',['class' => 'btn']) }}
      {!! Form::close() !!}
    </div>
  </div>

</div>
@endsection
