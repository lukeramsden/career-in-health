<?php

namespace App\Events;

use App\JobListingApplication;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreatedListingApplication implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  /**
   * The application that has been received
   *
   * @var JobListingApplication
   */
  public $application;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct(JobListingApplication $application)
  {
	$this->application = $application;
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
		"App.Company.{$this->application->job_listing->company_id}"
	  ),
	  new PrivateChannel(
		"App.Listing.{$this->application->job_listing_id}"
	  ),
	];
  }
}
