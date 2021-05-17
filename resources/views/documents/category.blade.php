@extends('layouts.app')
@section('content')

<link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
<div class="row">
  <div class="section">
    <div class="col m1 hide-on-med-and-down">
      @include('inc.sidebar')
    </div>
    <div class="col m11 s12">
    {{Breadcrumbs::render('documents')}} 
      <div class="row">
        <h4 class="flow-text"><i class="material-icons">folder_shared</i> Documents Categories
          @can('upload')
          <a href="/documents/create" class="btn btn-info right tooltipped" data-position="left" data-delay="50" data-tooltip="Upload New Document"><i class="material-icons">file_upload</i> New</a>
          @endcan
        </h4>
        <div class="divider"></div>
      </div>   
      <div class="card z-depth-2">        
        <div class="card-content">
          <!-- <div class="d-flex justify-content-end">
              <form action="/search" method="post" id="search-form">
                {{ csrf_field() }}
                <div class="input-field col-sm-12 right">
                  <i class="material-icons prefix">search</i>
                  <input type="text" name="search" id="search" placeholder="Search Here ...">
                  <label for="search"></label>
                </div>
              </form>              
          </div> -->            
          <div class="row">
            <div class="d-flex justify-content-around">
              @if(count($category))
                @foreach($category as $data)
                <!-- <div class="" id="tr_{{$data->id}}"> -->
                <div class="col s3" id="tr_{{$data->id}}">
                  <div class="card hoverable task" data-id="{{ $data->id }}">                            
                    <a href="/documents/category/{{ $data->id }}/subcategory">
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
      </div>
    </div>
  </div>
</div>
@endsection
