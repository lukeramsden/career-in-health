<?php

namespace App\Events;

use App\PrivateMessage;
use App\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreatedPrivateMessage implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  /**
   * The message that has been received
   *
   * @var PrivateMessage
   */
  public $message;

  /**
   * The user that received the notification
   *
   * @var User
   */
  public $user;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct(User $user, PrivateMessage $privateMessage)
  {
	$this->user    = $user;
	$this->message = $privateMessage;
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return \Illuminate\Broadcasting\Channel|array
   */
  public function broadcastOn()
  {
	return [
	  new PrivateChannel(
		"App.User.{$this->user->id}"
	  ),
	  new PrivateChannel(
		"App.PrivateMessage.Listing.{$this->message->job_listing_id}.Employee.{$this->message->employee_id}"
	  ),
	];
  }
}
