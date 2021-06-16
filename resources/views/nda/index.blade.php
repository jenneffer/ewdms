@extends('layouts.app')
@section('content')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
<style media="screen">
  .requests {
      display: flex;
      justify-content: flex-end;
      align-items: flex-end;
  }
  .reqBox1 {
    flex: 5
  }
  .reqBox2 {
    flex: 2;
  }
  .reqBox3 {
    flex: 1;
  }
</style>
<div class="row">
  <div class="section">
  <div class="col m1 hide-on-med-and-down">
    @include('inc.sidebar')
  </div>
  <div class="col m11">
      {{Breadcrumbs::render('nda_submission')}}    
      <!-- <div class="row">
        <h5 class="flow-text"><i class="material-icons">notifications</i> NDA Submissions</h5>       
        <div class="divider"></div> 
      </div> -->
      
      <div class="row col m11 s12">
        @if(count($submission) > 0)
          @foreach($submission as $sub)          
            <div class="card grey lighten-4 horizontal">
              <!-- <div class="card-image hide-on-med-and-down">
                <img src="/storage/images/sideytu1.jpg" height="140px">
              </div> -->
              <div class="card-stacked">
                <div class="card-content">
                  <div class="row requests">
                    <div class="reqBox1">
                      <p>Hi, I am <b>{{ $sub->name }}</b> , my email is <b>{{ $sub->email }}</b>.
                      <br>Here is my signed NDA form.</p>
                    </div>
                    <div class="reqBox2">
                      <p class="blue-text">{{ $sub->created_at }}</p>
                    </div>
                    <div class="reqBox3">
                    {!! Form::open() !!}
                    <a href="{{ route('nda.view_pdf',$sub->file_name) }}" class="btn tooltipped" data-position="top" data-delay="50" data-tooltip="View PDF"><i class="material-icons">pageview</i></a>
                    {!! Form::close() !!}
                    </div>
                    <div class="reqBox3">
                      {!! Form::open(['action' => ['NDAController@update',$sub->id], 'method' => 'PATCH']) !!}
                      {{ csrf_field() }}
                      <button type="submit" name="b1" class="btn tooltipped" data-position="top" data-delay="50" data-tooltip="Approve"><i class="material-icons">check</i></button>
                      {!! Form::close() !!}
                    </div>
                    <div class="reqBox3">
                      {!! Form::open() !!}                      
                      <a href="{{ route("nda.reject", [$sub->id]) }}" class=" btn tooltipped" data-position="top" data-delay="50" data-tooltip="Reject"><i class="material-icons">clear</i></a>                      
                      {!! Form::close() !!}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach          
        @else
          <div class="card">
            <div class="card-content">
              <h5 class="teal-text">No New Submission Has Been Received</h5>
            </div>
          </div>
        @endif

        <div class="card-content">
          <table class="bordered centered highlight" id="myDataTable">
            <thead>
              <tr>
                  <th>Name</th>
                  <th>Filename</th>
                  <th>Updated at</th>
                  <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @if(count($approved_submission) > 0)
                @foreach($approved_submission as $app_sub)
                  <tr>
                    <td>{{ $app_sub->name }}</td>
                    <td>{{ $app_sub->file_name }}</td>        
                    <td>{{ $app_sub->updated_at }}</td>                    
                    <td>
                    {!! Form::open() !!}
                    <a href="{{ route('nda.view_pdf',$app_sub->file_name) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="View PDF"><i class="material-icons">open_with</i></a>
                    {!! Form::close() !!}                   
                    </td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td colspan="4"><h5 class="teal-text">No NDA submission has been added</h5></td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
  </div>

  </div>
</div>
@endsection
