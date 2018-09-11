<?php

namespace App\Notifications;

use App\JobListingApplication;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyReceivedListingApplication extends Notification
{
    use Queueable;

    /** @var JobListingApplication */
	protected $application;

	/**
	 * Create a new notification instance.
	 *
	 * @param User $sender
	 */
	public function __construct(JobListingApplication $application)
	{
		$this->application = $application;
	}

	public static function getAction($application)
	{
		return action('JobListingController@showApplications', [
			'jobListing' => $application->job_listing,
			'highlight'  => $application->id,
		]);
	}

	/**
	 * @param User $notifiable
	 *
	 * @return array
	 */
	public function via($notifiable)
	{
		$via = ['database'];
		if ($notifiable->notificationPreferences()->first()->email_listing_application)
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
		return (new MailMessage)
			->subject("You have received an application from {$this->application->employee->full_name}.")
			->action("View The Application", self::getAction($this->application));
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
			'sender_name' => $this->application->employee->full_name,
			'body'        => $this->application->custom_cover_letter,
			'action'      => self::getAction($this->application),
		];
	}
}
