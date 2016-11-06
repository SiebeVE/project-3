<li>
	<p>User {{ $notification->data["toUser"]["firstname"] }} {{ $notification->data["toUser"]["lastname"] }} wants to
	{{$notification->data["type"]}} the book "{{ $notification->data["bookUser"]["book"]["title"] }}".</p>
	<p>Contact him/her now on <a href="mailto:{{ $notification->data["toUser"]["email"] }}">{{ $notification->data["toUser"]["email"] }}</a> to arrange an meeting with him/her.</p>
</li>