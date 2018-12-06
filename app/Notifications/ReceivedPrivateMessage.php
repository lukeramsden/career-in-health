<?php

namespace App\Notifications;

use App\PrivateMessage;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReceivedPrivateMessage extends Notification
{
  use Queueable;

  protected $privateMessage;

  /**
   * Create a new notification instance.
   *
   * @param User $sender
   */
  public function __construct(PrivateMessage $privateMessage)
  {
	$this->privateMessage = $privateMessage;
  }

  public function via($notifiable)
  {
	$via = ['database', 'broadcast'];
	if ($notifiable instanceof User
	  && $notifiable->notificationPreferences()->first()->email_private_message)
	  array_push($via, 'mail');
	return $via;
  }

  /**
   * @param $notifiable
   *
   * @return MailMessage
   * @throws \Exception
   */
  public function toMail($notifiable)
  {
	$s = self::getSenderName();
	return (new MailMessage)
	  ->subject("You have received a message from $s.")
	  ->action("View Your Messages With $s", self::getAction($this->privateMessage));
  }

  /**
   * @throws \Exception
   */
  protected function getSenderName()
  {
	$pm = $this->privateMessage;

	if ($pm->direction === 'to_company')
	  return $pm->employee->full_name;
	elseif ($pm->direction === 'to_employee')
	  return $pm->company->name;

	throw new \Exception();
  }

  protected function getAction($pm)
  {
	return $url = $pm->direction === 'to_company' ?
	  route('account.private-message.show-company', [$pm->job_listing, $pm->employee])
	  : route('account.private-message.show-employee', $pm->job_listing);
  }

  /**
   * @param $notifiable
   *
   * @return array
   * @throws \Exception
   */
  public function toDatabase($notifiable)
  {
	return [
	  'sender_name' => self::getSenderName(),
	  'body'        => $this->privateMessage->body,
	  'action'      => self::getAction($this->privateMessage),
	  'message_id'  => $this->privateMessage->id,
	];
  }

  /**
   * Get the broadcastable representation of the notification.
   *
   * @param  mixed $notifiable
   *
   * @return BroadcastMessage
   * @throws \Exception
   */
  public function toBroadcast($notifiable)
  {
	return new BroadcastMessage([
	  'data'       => [
		'sender_name' => self::getSenderName(),
		'body'        => $this->privateMessage->body,
		'action'      => self::getAction($this->privateMessage),
		'message_id'  => $this->privateMessage->id,
	  ],
	  'created_at' => now()->toDateTimeString(),
	  'updated_at' => now()->toDateTimeString(),
	  'read_at'    => null,
	]);
  }
}
