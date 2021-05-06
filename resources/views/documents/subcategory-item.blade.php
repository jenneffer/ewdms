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
      {{Breadcrumbs::render('subcategory', $parent_id, $id)}}
        <div class="card">
            <div class="row col-lg-12">
              @can('upload')
                <a href="/documents/create" class="btn left tooltipped" data-position="left" data-delay="50" data-tooltip="Upload New Document"><i class="material-icons">file_upload</i> New</a>
              @endcan
            </div>
            <div class="col-lg-12">
              <div class="d-flex justify-content-end">
                  <form action="/search" method="post" id="search-form">
                    {{ csrf_field() }}
                    <div class="input-field col-sm-12 right">
                      <i class="material-icons prefix">search</i>
                      <input type="text" name="search" id="search" placeholder="Search Here ...">
                      <label for="search"></label>
                    </div>
                  </form>              
              </div>
              <br>
            </div>
            <div class="d-flex justify-content-around">
                @if(count($subcategoryItem))
                  @foreach($subcategoryItem as $data)
                  <!-- <div class="" id="tr_{{$data->id}}"> -->
                  <div class="col m2 s6 h75" id="tr_{{$data->id}}">
                    <div class="card hoverable indigo lighten-5 task" data-id="{{ $data->id }}">                            
                      <a href="/documents/category/{{$data->id}}">
                        <div class="card-content2 center">                       
                          <i class="material-icons">folder_open</i>                        
                          <h6>{{App\Category::findName($data->id)}}</h6>                                               
                        </div>
                      </a>
                    </div>
                  </div>                   
                  @endforeach
                @endif
            </div>        
          </div>
      </div>
    </main>
  </div>
</div>  
@endsection
