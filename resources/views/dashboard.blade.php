@extends('layouts.app')

@section('content')
<style>
.hoverable:hover{
  transform: scale(1.05);
  box-shadow: 0 10px 20px rgba(0,0,0,.12), 0 4px 8px rgba(0,0,0,.06);
}
</style>
  <!-- Admin Dashboard -->
  <div class="container">
    <br>
    <div class="row">
      <div class="col s12">
        <h3 class="flow-text"><i class="material-icons">settings</i> Dashboard</h3>
        <div class="divider"></div>
      </div>
      <div class="section">        
        <a href="/documents">
          <div class="col m4 s6">
            <div class="card hoverable indigo lighten-1 white-text">
              <div class="card-content">
                <p class="center"><i class="large material-icons">folder</i></p>
                <h4 class="center-align flow-text">Documents
                  <!-- <span class="new badge white-text">336</span> belum buat-->
                </h4>
              </div>
            </div>
          </div>
        </a>
        @hasrole('Admin|Moderator')
        <a href="/users">
          <div class="col m4 s6">
            <div class="card hoverable indigo lighten-1 white-text">
              <div class="card-content">
                <p class="center"><i class="large material-icons">person</i></p>
                <h4 class="center-align flow-text">Users
                  <!-- <span class="new badge white-text">45</span> belum buat-->
                </h4>
              </div>
            </div>
          </div>
        </a>
        <!-- <a href="/departments">
          <div class="col m4 s6">
            <div class="card hoverable indigo lighten-1 white-text">
              <div class="card-content">
                <p class="center"><i class="large material-icons">group</i></p>
                <h4 class="center-align flow-text">Departments</h4>
              </div>
            </div>
          </div>
        </a> -->
        <a href="/roles">
          <div class="col m4 s6">
            <div class="card hoverable indigo lighten-1 white-text">
              <div class="card-content">
                <p class="center"><i class="large material-icons">assignment_ind</i></p>
                <h4 class="center-align flow-text">Roles &amp; Permissions</h4>
              </div>
            </div>
          </div>
        </a>
        <a href="/logs">
          <div class="col m4 s6">
            <div class="card hoverable indigo lighten-1 white-text">
              <div class="card-content">
                <p class="center"><i class="large material-icons">view_list</i></p>
                <h4 class="center-align flow-text">Logs</h4>
              </div>
            </div>
          </div>
        </a>        
        <a href="/categories">
          <div class="col m4 s6">
            <div class="card hoverable indigo lighten-1 white-text">
              <div class="card-content">
                <p class="center"><i class="large material-icons">class</i></p>
                <h4 class="center-align flow-text">Categories</h4>
              </div>
            </div>
          </div>
        </a>
        @endhasrole
      </div>
    </div>
  </div>
@endsection
