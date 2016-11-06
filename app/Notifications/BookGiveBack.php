<?php

namespace App\Notifications;

use App\BookUser;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookGiveBack extends Notification
{
    use Queueable;

	private $fromUser, $bookUser;

	/**
	 * Create a new notification instance.
	 *
	 * @param User $fromUser
	 * @param BookUser $bookUser
	 */
    public function __construct(User $fromUser, BookUser $bookUser)
    {
        $this->fromUser = $fromUser;
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
            "user" => $this->fromUser,
	        "book" => $this->bookUser->book,
        ];
    }
}
