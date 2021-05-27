@extends('layouts.app')
@section('content')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
<div class="row">
  <div class="section">
  <div class="col m1 hide-on-med-and-down">
    @include('inc.sidebar')
  </div>
  <div class="col m11 s12">
    {{Breadcrumbs::render('subcategory', $parent_id, $id)}}
    <div class="row">
      @can('upload')
      <h5 class="flow-text"><a href="/documents/create" class="btn right tooltipped" data-position="left" data-delay="50" data-tooltip="Upload New Document"><i class="material-icons">file_upload</i> New</a></h5>
      @endcan        
    </div>
    <div class="card"> 
      <div class="card-content">           
        <!-- <div class="col-sm-12">
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
        </div>   -->
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
          
            @if(count($subcategory) > 0)
            @foreach($subcategory as $sub)
            <tr>
              <td>&nbsp;</td>
              <td><a href="{{route("documents.category",[$sub->id])}}">{{App\Category::findName($sub->id)}} ({{$sub->doc_count}})</a></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            @endforeach
            @else
              <tr>
                <td colspan="7"><h5 class="teal-text">No Document has been uploaded</h5></td>
              </tr>
            @endif
            @if(count($docs) > 0)
            @foreach($docs as $doc)
            <tr>
              <td>&nbsp;</td>
              <td>{{$doc->name}}</td>
              <td>{{App\User::findName($doc->user_id)}}</td>
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
                <a href="{{ route("documents.show", [$doc->id]) }}" class="tooltipped" data-position="left" data-delay="50" data-tooltip="View Details"><i class="material-icons">visibility</i></a>
                {!! Form::close() !!}
                {!! Form::open() !!}
                <a href="{{ route("view.embed", [$doc->id]) }}" class="tooltipped" data-position="left" data-delay="50" data-tooltip="Open"><i class="material-icons">open_with</i></a>
                {!! Form::close() !!}
                @endcan

                {!! Form::open() !!}
                @can('edit')
                <a href="{{ route("documents.edit", [$doc->id]) }}" class="tooltipped" data-position="left" data-delay="50" data-tooltip="Edit"><i class="material-icons">mode_edit</i></a>
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
            @endif
          </tbody>
        </table>    
      </div>            
    </div>
  </div>
  </div>
</div>  
@endsection
