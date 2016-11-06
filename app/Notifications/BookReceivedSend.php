<?php

namespace App\Notifications;

use App\BookUser;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookReceivedSend extends Notification
{
    use Queueable;

	private $type, $fromUser, $bookUser;

	/**
	 * Create a new notification instance.
	 *
	 * @param $type
	 * @param User $fromUser
	 * @param BookUser $bookUser
	 */
	public function __construct ($type, User $fromUser, BookUser $bookUser) {
		$this->type = $type;
		$this->fromUser = $fromUser;
		$this->bookUser = $bookUser;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return array
	 */
	public function via ($notifiable) {
		return ['database'];
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return array
	 */
	public function toArray ($notifiable) {
		return [
			"type"   => $this->type,
			"fromUser" => $this->fromUser,
			"book"   => $this->bookUser->book,
		];
	}
}
