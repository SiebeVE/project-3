<li>
	<p>You requested the book {{ $notification->data["book"]["title"] }} from the user {{ $notification->data["fromUser"]["firstname"] }} {{ $notification->data["fromUser"]["lastname"] }}.</p>
	<p>He/She wil contact you soon to arrange a meeting.</p>
	<p><a href="{{ url('/book/transaction/'.$notification->data["type"].'/confirm/'.$notification->data["transaction"]["id"]) }}">Click here if you received the book.</a></p>
</li>