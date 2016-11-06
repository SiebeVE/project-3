<?php

namespace App\Notifications;

use App\BookTransaction;
use App\BookUser;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BorrowRequest extends Notification
{
	use Queueable;

	private $toUser, $book, $type;

	/**
	 * Create a new notification instance.
	 *
	 * @param User $toUser
	 * @param BookUser $book
	 * @param $type
	 */
	public function __construct (User $toUser, BookUser $book, $type) {
		$this->toUser = $toUser;
		$this->book = $book;
		$this->type = $type;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return array
	 */
	public function via ($notifiable) {
		return ['mail', 'database'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail ($notifiable) {
		return (new MailMessage)
			->line('Great news, ' . $this->toUser->firstname . ' ' . $this->toUser->lastname . ' wants to ' . $this->type . ' your book"' . $this->book->book->title . '"')
			->line('Contact ' . $this->toUser->firstname . ' ' . $this->toUser->lastname . ' now on ' . $this->toUser->email . ' to arrange an meeting with him/her.');
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
			"type"        => $this->type,
			"bookUser"    => $this->book,
			"toUser"      => $this->toUser,
		];
	}
}
