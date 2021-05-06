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
        {{Breadcrumbs::render('editcategories', $category)}}      
        <div class="col-lg-12">
            <h3 class="flow-text"><i class="material-icons">class</i> Edit Categories               
                <button data-target="#modal1" data-toggle="modal" class="btn btn-info right">Add New</button>
            </h3>      
        </div>
        <div class="divider"></div>
        {!! Form::open(['action' => ['CategoriesController@update',$category->id], 'method' => 'PATCH', 'class' => 'col m12']) !!}
        <div class="card z-depth-2">
          <div class="card-content">
            <div class="row">
              <div class="col m6 input-field">
                <i class="material-icons prefix">class</i>
                {{ Form::text('name', $category->name, ['class' => 'validate', 'id' => 'category']) }}
                <label for="category">Category Name</label>
              </div>      
            </div>
            <div class="row center">
              <div class="col m6 input-field">
                {{ Form::submit('Save Changes', ['class' => 'btn waves-effect waves-light']) }}
              </div>
            </div>              
          </div>
        </div>
        </div>
        </main>
    </div>
</div>

<!-- Modal -->
<!-- Modal Structure -->
<div id="modal1" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Add Category</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
      {!! Form::open(['action' => 'CategoriesController@store', 'method' => 'POST', 'class' => 'col s12']) !!}
        <div class="col s12 input-field">
          <i class="material-icons prefix">class</i>
          {{ Form::text('name','',['class' => 'validate', 'id' => 'category']) }}
          <label for="category">Category Name</label>
        </div>
    </div>
    <div class="modal-footer">
      {{ Form::submit('submit',['class' => 'btn']) }}
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection
