<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConfirmEmail extends Notification
{
	use Queueable;

	protected $user;

	public function __construct($user)
	{
		$this->user = $user;
	}

	public function via($notifiable)
	{
		return ['mail'];
	}

	public function toMail($notifiable)
	{
		return (new MailMessage)
			->line('You are receiving this email because an account has been created with your email.')
			->action('Confirm Email', route('confirm-email', $this->user->confirmation_code))
			->line('If you did not create this account, please do not confirm your email.');
	}
}
