@extends('layouts.app')
@section('content')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
<div class="row">
  <div class="section">
    <div class="col m1 hide-on-med-and-down">
      @include('inc.sidebar')
    </div>
    <div class="col m11 s12">    
    {{Breadcrumbs::render('view_nda', $file_name)}}    
      <div class="row">
          <h5 class="flow-text">
            <a href="{{ url()->previous() }}" class="waves-effect waves-light btn right tooltipped" data-position="left" data-tooltip="Go Back"><i class="material-icons">arrow_back</i> Back</a>            
          </h5>
          <!-- <div class="divider"></div> -->
      </div> 
      <div class="card z-depth-2">
          <div class="card-content">
            <iframe src="/images/NDA Signed/{{$file_name}}" frameborder="0" height="500px" width="100%"></iframe>            
          </div>
      </div>        
    </div>
  </div>
</div>

@endsection
