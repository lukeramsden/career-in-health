<?php

namespace App\Events;

use App\PrivateMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

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
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PrivateMessage $privateMessage)
    {
        $this->message = $privateMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel(
        	"App.PrivateMessage.Listing.{$this->message->job_listing_id}.Employee.{$this->message->employee_id}"
		);
    }
}
