<?php

namespace App\Notifications;

use App\BookUser;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookGiveBackSend extends Notification
{
    use Queueable;

	private $toUser, $bookUser;

	/**
	 * Create a new notification instance.
	 *
	 * @param User $toUser
	 * @param BookUser $bookUser
	 */
	public function __construct(User $toUser, BookUser $bookUser)
	{
		$this->toUser = $toUser;
		$this->bookUser = $bookUser;
	}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            "user" => $this->toUser,
	        "book" => $this->bookUser->book,
        ];
    }
}
