@extends('layouts.app')

@section('header_left')
	<a href="/">
		<i class="fa fa-chevron-left" aria-hidden="true"></i>
		Home
	</a>
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2" id="change-column">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Notifications</h3>
					</div>
					<div class="panel-body">
						<h2>New notifications</h2>
						<ul>
							@if(count($notifications["unread"]) > 0)
								@foreach($notifications["unread"] as $notification)
									@include('notification.'.snake_case(class_basename($notification->type)))
								@endforeach
							@else
								<li>You don't have any unread notifications.</li>
							@endif
						</ul>
						<h2>Read notifications</h2>
						<ul>
							@if(count($notifications["read"]) > 0)
								@foreach($notifications["read"] as $notification)
									@include('notification.'.snake_case(class_basename($notification->type)))
								@endforeach
							@else
								<li>You don't have any read notifications.</li>
							@endif
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection