<?php

namespace App\Notifications;

use App\BookTransaction;
use App\BookUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BorrowRequestSend extends Notification
{
	use Queueable;

	private $type, $transaction, $book, $fromUser;

	/**
	 * Create a new notification instance.
	 *
	 * @param $type
	 * @param BookTransaction $transaction
	 * @param BookUser $bookUser
	 */
	public function __construct ($type, BookTransaction $transaction, BookUser $bookUser) {
		$this->type = $type;
		$this->transaction = $transaction;
		$this->book = $bookUser->book;
		$this->fromUser = $bookUser->user;
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
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed $notifiable
	 *
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail ($notifiable) {
		return (new MailMessage)
			->line('The introduction to the notification.')
			->action('Notification Action', 'https://laravel.com')
			->line('Thank you for using our application!');
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
			"transaction" => $this->transaction,
			"book"        => $this->book,
			"fromUser"    => $this->fromUser,
		];
	}
}
