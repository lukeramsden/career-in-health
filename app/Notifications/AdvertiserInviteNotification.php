<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Advertising\AdvertiserInvite;
use Illuminate\Support\Facades\Auth;

class AdvertiserInviteNotification extends Notification
{
    use Queueable;

	/* @var AdvertiserInvite $invite */
	protected $invite;

	public function __construct(AdvertiserInvite $invite)
	{
		$this->invite = $invite;
	}

	public function via($notifiable)
	{
		return ['mail'];
	}

	public function toMail($notifiable)
	{
		return (new MailMessage)
			->line('You have been invited to be an advertiser at Career in Health.')
			->action('Accept Invite', route('advertising.accept-invite.show', [$this->invite->accept_code]));
	}
}
