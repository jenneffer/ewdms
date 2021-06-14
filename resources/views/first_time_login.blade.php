<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EDMS') }}</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="{{ asset('iconfont/material-icons.css') }}">
    <!-- Materialize css -->
    <link rel="stylesheet" href="{{ asset('materialize-css/css/materialize.min.css') }}">
    <!-- datatables -->
    <link rel="stylesheet" href="{{ asset('DataTables/datatables.min.css') }}">
    <!-- favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="/storage/images/favicon.ico">
    
    <style>
      body {
        display: flex;
        min-height: 100vh;
        flex-direction: column;
      }
      main {
        flex: 1 0 auto;
      }      
    </style>
</head>
<body>
  <!-- @include('inc.spinner') -->
  <main>
    <div class="container">
      <div class="row">
        {!! Form::open(['action' => ['NDAController@store'], 'method' => 'POST', 'class' => 'col s12', 'files' => true]) !!}     
        <div class="col s12">
            <h3 class="flow-text"><i class="material-icons">library_books</i> Non Disclosure Agreement Form</h3>
            <div class="divider"></div>
        </div>
        <div class="section">
        <div class="col m12">          
            <p>Welcome to EW DMS, please follow the steps below :</p>
        </div> 
        <div class="col m12">
            <p>1. Download NDA Form here.<a href="/downloadNDA"> <i class="material-icons">file_download</i></a></p>
        </div>  
        <div class="col m12">
            <p>2. Please go thru the documents, acknowledge and sign every page. </p>
        </div>         
        <div class="col m12">
            <p>3. Once completed, upload the signed document here (Only PDF files allowed). {{ Form::file('nda_form',['class' => 'validate', 'id' => 'nda_form']) }} </p>              
        </div>  
        <div class="col m12">
            <p>4. You will be redirected to login page after submitting the document. Once the approval process was done, the link to access the system will be send via email.</p>              
        </div> 
        
        <div class="col m12">
            {{ Form::submit('submit', ['class' => 'btn']) }}
        </div>  
        </div>     
      </div>      
    </div>
  </main>
  @include('inc.footer')

  <!-- Scripts -->
  @include('inc.scripts')
  <!-- Right click context-menu -->
  <script src="{{ asset('js/context-menu.js') }}"></script>
  <!-- MESSAGES -->
  @include('inc.messages')
</body>
</html>
