<footer class="page-footer indigo darken-4">
  <div class="container">
    <div class="row">
      <div class="col l6 s12">
        <h5 class="white-text">European Wellness</h5>
        <p class="grey-text text-lighten-4">Electronics Document Management System</p>
      </div>
    </div>
  </div>
  <div class="footer-copyright indigo darken-3">
    <div class="container">
    © {{ Date('Y') }} Copyright @ EUROPEAN WELLNESS
    <a class="grey-text text-lighten-4 right" href="#modal2" data-target="#modal2" data-toggle="modal">Help</a>
    </div>
  </div>
</footer>

<!-- Modal For Help -->
<!-- <div id="modal2" class="modal">
  <div class="modal-content">
    <div class="modal-header">
    <h4 class="modal-title">Help</h4>
    </div>    
    <div class="modal-body">
      <p>
        This guide will lead you the way to get friendly with EDMS, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
      </p>
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat " data-dismiss="modal">OK</a>
    </div>
  </div>  
</div> -->
<div id="modal2" class="modal">
  <div class="modal-content">
    <div class="modal-header">
    <h4 class="modal-title">Helpdesk/Enquiries</h4>
    </div>    
    <div class="modal-body">
    {!! Form::open(['action' => ['EnquiriesController@store'], 'method' => 'POST', 'class' => 'col s12', 'files' => true]) !!}
      <div class="col s12 input-field">
          <i class="material-icons prefix">person</i>
          {{ Form::text('name','', ['class' => 'validate', 'id' => 'name']) }}
          <label for="name">Your Name</label>
      </div> 
      <div class="col s12 input-field">
          <i class="material-icons prefix">email</i>
          {{ Form::email('email','', ['class' => 'validate', 'id' => 'email']) }}
          <label for="email">Your Email</label>
      </div> 
      <div class="col s12 input-field">
          <i class="material-icons prefix">title</i>
          {{ Form::text('title','', ['class' => 'validate', 'id' => 'title']) }}
          <label for="title">Title</label>
      </div> 
      <div class="col s12 input-field">
          <i class="material-icons prefix">comment</i>
          {{ Form::text('content','', ['class' => 'validate', 'id' => 'content']) }}
          <label for="content">Content</label>
      </div> 
      <div class="col s12 input-field">
          <i class="material-icons prefix">attachment</i>
          {{ Form::file('attachment[]',['class' => 'validate', 'id' => 'attachment', 'multiple' => 'multiple']) }}          
      </div>      
      <div class="col s12">
        <span style="color:red;">**</span><label> You can attach multiple files</label>
      </div> 
    </div>
    <div class="modal-footer">
      <!-- <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat " data-dismiss="modal">OK</a> -->
      {{ Form::submit('submit', ['class' => 'btn']) }}
    </div>    
    {!! Form::close() !!}
  </div>  
</div>
