<?php

namespace App\Notifications;

use App\CompanyUserInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyInvite extends Notification
{
	use Queueable;

	protected $invite;

	public function __construct(CompanyUserInvite $invite)
	{
		$this->$invite = $invite;
	}

	public function via($notifiable)
	{
		return ['mail'];
	}

	public function toMail($notifiable)
	{
		return (new MailMessage)
			->line('You have been invited to a company at Career in Health.')
			->action('Accept Invite', url('/'))
			->line('Thank you for using our application!');
	}
}
