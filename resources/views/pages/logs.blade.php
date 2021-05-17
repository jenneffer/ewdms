@extends('layouts.app')

@section('content')
<link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
<div class="row">
	<div class="section">
	<div class="col m1 hide-on-med-and-down">
      @include('inc.sidebar')
    </div>
    <div class="col m11 s12">
		<div class="row">
			<h5 class="flow-text"><i class="material-icons">view_list</i> Users' Activities
			@can('root')
			<a href="logsdel" data-position="left" data-delay="50" data-tooltip="Clear All" class="right tooltipped pulse"><i class="material-icons">clear_all</i></a>
			@endcan
			</h5>
			<div class="divider"></div>
		</div>
		<div class="card z-depth-2">
        	<div class="card-content">
			<table class="bordered centered highlight" id="log-table">
				<thead>
				<tr>
					<th>No.</th>
					<th>Date</th>
					<th>Subject</th>
					<th>URL</th>
					<th>Method</th>
					<th>IP</th>
					<th>Agent</th>
					<th>User ID</th>
				</tr>
				</thead>
				<tbody>
				@if(count($logs) > 0)
					@foreach($logs as $key => $log)
					<tr>
						<td>{{ ++$key }}.</td>
						<td>{{ $log->created_at->toDayDateTimeString() }}</td>
						<td>{{ $log->subject }}</td>
						<td>{{ $log->url }}</td>
						<td>{{ $log->method }}</td>
						<td>{{ $log->ip }}</td>
						<td>{{ $log->agent }}</td>
						<td>{{ App\User::findName($log->user_id) }}</td>
					</tr>
					@endforeach
				@else
                <tr>
                	<td colspan="8"><h5 class="teal-text">No Logs have been recorded yet ...</h5></td>
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
