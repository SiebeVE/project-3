<li>
	<p>User {{ $notification->data["toUser"]["firstname"] }} {{ $notification->data["toUser"]["lastname"] }} wants to
		let you know he/she received your book: "{{ $notification->data["book"]["title"] }}".</p>
	@if($notification->data["type"] == "borrow")
		<p><a href="{{ url('/book/transaction/borrow/confirm/giveBack/'.$notification->data["transaction"]["id"]) }}">Click here if you received it back again.</a></p>
	@endif
</li>