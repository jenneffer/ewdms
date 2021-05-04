@extends('layouts.app')

@section('content')
<style>
  .card-content2 {
    /* padding: 10px 7px; */
    border-radius: 4px;
    background: #fff;
    box-shadow: 0 6px 10px rgba(0,0,0,.08), 0 0 6px rgba(0,0,0,.05);
    transition: .3s transform cubic-bezier(.155,1.105,.295,1.12),.3s box-shadow,.3s -webkit-transform cubic-bezier(.155,1.105,.295,1.12);
    padding: 14px 80px 18px 36px;
    cursor: pointer;
  }
  .card-content2:hover{
      transform: scale(1.05);
    box-shadow: 0 10px 20px rgba(0,0,0,.12), 0 4px 8px rgba(0,0,0,.06);
  }
  /* --- for right click menu --- */
  *,
  *::before,
  *::after {
    box-sizing: border-box;
  }
  .task i {
    color: orange;
    font-size: 35px;
  }
  /* context-menu */
  .context-menu {
    padding: 0 5px;
    margin: 0;
    background: #f7f7f7;
    font-size: 15px;
    display: none;
    position: absolute;
    z-index: 10;
    box-shadow: 0 4px 5px 0 rgba(0,0,0,0.14), 0 1px 10px 0 rgba(0,0,0,0.12), 0 2px 4px -1px rgba(0,0,0,0.3);
  }
  .context-menu--active {
    display: block;
  }
  .context-menu_items {
    margin: 0;
  }
  .context-menu_item {
    border-bottom: 1px solid #ddd;
    padding: 12px 30px;
  }
  .context-menu_item:last-child {
    border-bottom: none;
  }
  .context-menu_item:hover {
    background: #fff;
  }
  .context-menu_item i {
    margin: 0;
    padding: 0;
  }
  .context-menu_item p {
    display: inline;
    margin-left: 10px;
  }
  .unshow {
    display: none;
  }
</style>
<div class="row">
  <div class="section">
    <div class="col m1 hide-on-med-and-down">
      @include('inc.sidebar')
    </div>
    <div class="col m11 s12">
      <div class="row">
        <h3 class="flow-text"><i class="material-icons">folder</i>Documents Category
        @can('upload')
          <a href="/documents/create" class="btn waves-effect waves-light right tooltipped" data-position="left" data-delay="50" data-tooltip="Upload New Document"><i class="material-icons">file_upload</i> New</a>
        @endcan
        </h3>
        <div class="divider"></div>
      </div>
      <div class="card">
        <div class="card-content">
          <div id="folderView">
              <div class="row">
                <!-- <form action="/sort" method="post" id="sort-form">
                  {{ csrf_field() }}
                  <div class="input-field col m2 s12">
                    <select name="filetype" id="sort">
                      <option value="" disabled selected>Choose</option>                      
                      <option value="application/vnd.openxmlformats-officedocument.wordprocessingml.document">Word Documents</option>
                      <option value="">Others</option>
                    </select>
                    <label>Sort By File Type</label>
                  </div>
                </form> -->
                <form action="/search" method="post" id="search-form">
                  {{ csrf_field() }}
                  <div class="input-field col m4 s12 right">
                    <i class="material-icons prefix">search</i>
                    <input type="text" name="search" id="search" placeholder="Search Here ...">
                    <label for="search"></label>
                  </div>
                </form>
              </div>
              <br>              
              <div class="row">
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
      </div>
    </div>
  </div>
</div>
@endsection
