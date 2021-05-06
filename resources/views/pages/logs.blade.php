@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-sm-1">
      @include('inc.sidebar')
    </div>
    <div class="col-sm-11">
		<main>
			<div class="container-fluid">
				<br>
				<div class="col-sm-12">
					<h3 class="flow-text"><i class="material-icons">view_list</i> Users' Activities
					@can('root')
					<a href="logsdel" data-position="left" data-delay="50" data-tooltip="Clear All" class="right tooltipped pulse"><i class="material-icons">clear_all</i></a>
					@endcan
					</h3>
					<div class="divider"></div>
				</div>
				<div class="col-sm-12">
				@if(count($logs) > 0)
					@foreach($logs as $key => $log)
					<div class="card horizontal hoverable blue-grey darken-1">
						<div class="card-content white-text">
							<div class="col s3">
								<h4 class="center yellow-text z-depth-5">{{ ++$key }}</h4>
								<br>
								<blockquote>{{ $log->created_at->toDayDateTimeString() }}</blockquote>
							</div>
							<div class="col s9">
								<blockquote>
									<ul>
										<li>Subject => {{ $log->subject }}</li>
										<li>URL => {{ $log->url }}</li>
										<li>Method => {{ $log->method }}</li>
										<li>IP => {{ $log->ip }}</li>
										<li>Agent => {{ $log->agent }}</li>
										<li>User ID => {{ $log->user_id }}</li>
									</ul>
								</blockquote>
							</div>
						</div>
					</div>
					@endforeach
				@else
					<div class="card-panel blue-grey">
						<p class="flow-text white-text">
							No Logs have been recorded yet ...
						</p>
					</div>
				@endif
				</div>
			</div>
		</main>
    </div>
</div>
@endsection
