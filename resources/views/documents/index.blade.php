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
    {{Breadcrumbs::render('subcategoryitemlist', $parent_id, $child_id, $id)}}  
    <div class="card">
        <div class="row col-lg-12">
          @can('upload')
            <a href="/documents/create" class="btn left tooltipped" data-position="left" data-delay="50" data-tooltip="Upload New Document"><i class="material-icons">file_upload</i> New</a>
          @endcan
        </div>
        <div class="col-lg-12">
          <div class="d-flex justify-content-between">
              <div class="col-sm-4 switch">
                <label>
                  Grid View
                  <input type="checkbox">
                  <span class="lever"></span>
                  Table View
                </label>
              </div>
              <div class="col-sm-4">
                <form action="/search" method="post" id="search-form">
                  {{ csrf_field() }}
                  <div class="input-field col-sm-12 right">
                    <i class="material-icons prefix">search</i>
                    <input type="text" name="search" id="search" placeholder="Search Here ...">
                    <label for="search"></label>
                  </div>
                </form> 
              </div>           
          </div>
          <br>
          <div id="folderView">
          <div class="d-flex flex-row justify-content-start">
            @if(count($docs) > 0)
              @foreach($docs as $doc)
              <div class="col-sm-3" id="tr_{{$doc->id}}">
                <div class="card hoverable indigo lighten-5 task" data-id="{{ $doc->id }}">                            
                  <input type="checkbox" class="filled-in sub_chk" id="chk_{{$doc->id}}" data-id="{{$doc->id}}">
                  <label for="chk_{{$doc->id}}"></label>
                  <a href="/documents/{{$doc->id}}">
                    <div class="card-content2 center">                       
                      <i class="material-icons">folder_open</i>                        
                      <h6>{{$doc->name}}</h6>                                               
                    </div>
                  </a>
                </div>
              </div>               
              @endforeach
            @else
              <h5 class="teal-text">No Document has been uploaded</h5>
            @endif
          </div>
          </div>
          <div id="tableView" class="unshow">
              <table class="bordered centered highlight responsive-table" id="myDataTable">
                <thead>
                  <tr>
                      <th></th>
                      <th>File Name</th>
                      <th>Owner</th>
                      {{-- <th>Department</th> --}}
                      <th>Category</th>
                      <th>Uploaded At</th>
                      <th>Expires At</th>
                      <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($docs) > 0)
                    @foreach($docs as $doc)
                    <tr id="tr_{{$doc->id}}">
                      <td>
                        <input type="checkbox" id="chk_{{ $doc->id }}" class="sub_chk" data-id="{{$doc->id}}">
                        <label for="chk_{{ $doc->id }}"></label>
                      </td>
                      <td>{{ $doc->name }}</td>
                      <td>{{ $doc->user_id }}</td>
                      <!-- {{-- <td>{{ $doc->user->department['dptName'] }}</td> --}} -->
                      <td>{{App\Category::findName($doc->category_id)}}</td>
                      <td>{{ $doc->created_at }}</td>
                      <td>
                        @if($doc->isExpire)
                          {{ $doc->expires_at }}
                        @else
                          No Expiration
                        @endif
                      </td>
                      <td>
                        @can('read')
                        {!! Form::open() !!}
                        <a href="../{{ $doc->id }}" class="tooltipped" data-position="left" data-delay="50" data-tooltip="View Details"><i class="material-icons">visibility</i></a>
                        {!! Form::close() !!}
                        {!! Form::open() !!}
                        <a href="../open/{{ $doc->id }}" target="_blank" class="tooltipped" data-position="left" data-delay="50" data-tooltip="Open"><i class="material-icons">open_with</i></a>
                        {!! Form::close() !!}
                        @endcan
                        <!-- {!! Form::open() !!}
                        @can('download')
                        <a href="../download/{{ $doc->id }}" class="tooltipped" data-position="left" data-delay="50" data-tooltip="Download"><i class="material-icons">file_download</i></a>
                        @endcan
                        {!! Form::close() !!} -->
                        <!-- SHARE using link -->
                        <!-- {!! Form::open(['action' => ['ShareController@update', $doc->id], 'method' => 'PATCH', 'id' => 'form-share-documents-' . $doc->id]) !!}
                        @can('shared')
                        <a href="" class="data-share tooltipped" data-position="left" data-delay="50" data-tooltip="Share" data-form="documents-{{ $doc->id }}"><i class="material-icons">share</i></a>
                        @endcan
                        {!! Form::close() !!} -->
                        {!! Form::open() !!}
                        @can('edit')
                        <a href="../{{ $doc->id }}/edit" class="tooltipped" data-position="left" data-delay="50" data-tooltip="Edit"><i class="material-icons">mode_edit</i></a>
                        @endcan
                        {!! Form::close() !!}
                        <!-- DELETE using link -->
                        {!! Form::open(['action' => ['DocumentsController@destroy', $doc->id],
                        'method' => 'DELETE', 'id' => 'form-delete-documents-' . $doc->id]) !!}
                        @can('delete')
                        <a href="" class="data-delete tooltipped" data-position="left" data-delay="50" data-tooltip="Delete" data-form="documents-{{ $doc->id }}"><i class="material-icons">delete</i></a>
                        @endcan
                        {!! Form::close() !!}
                      </td>
                    </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="6"><h5 class="teal-text">No Document has been uploaded</h5></td>
                    </tr>
                  @endif
                </tbody>
              </table>
          </div>
        </div>
    </div>
    </div>
    </main>
  </div>
</div>

<!-- right click menu -->
<div id="context-menu" class="context-menu">
  <ul class="context-menu_items">
    <li class="context-menu_item">
      <a href="documents/open/15" class="context-menu_link" data-action="Open">
        <i class="material-icons">open_with</i><p>Open</p>
      </a>
    </li>
    <li class="context-menu_item">
      <a href="#" class="context-menu_link" data-action="Share">
        <i class="material-icons">share</i><p>Share</p>
      </a>
    </li>
    <li class="context-menu_item">
      <a href="documents/15/edit" class="context-menu_link" data-action="Edit">
        <i class="material-icons">edit</i><p>Edit</p>
      </a>
    </li>
    <li class="context-menu_item">
      <a href="#" class="context-menu_link" data-action="Delete">
        <i class="material-icons">delete</i><p>Delete</p>
      </a>
    </li>
  </ul>
</div>
@endsection
