<?php

namespace App\Notifications;

use App\BookTransaction;
use App\BookUser;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookReceived extends Notification
{
	use Queueable;

	private $type, $toUser, $bookUser, $transaction;

	/**
	 * Create a new notification instance.
	 *
	 * @param $type
	 * @param User $toUser
	 * @param BookUser $bookUser
	 * @param BookTransaction $transaction
	 */
	public function __construct ($type, User $toUser, BookUser $bookUser, BookTransaction $transaction) {
		$this->type = $type;
		$this->toUser = $toUser;
		$this->bookUser = $bookUser;
		$this->transaction = $transaction;
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
			"type"        => $this->type,
			"toUser"      => $this->toUser,
			"book"        => $this->bookUser->book,
			"transaction" => $this->transaction,
		];
	}
}
