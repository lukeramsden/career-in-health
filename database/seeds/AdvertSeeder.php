<?php

use App\Advertising\Advert;
use App\Advertising\HomePageAdvert;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

class AdvertSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 * @throws Throwable
	 */
	public function run()
	{
		/**
		 * Create 10 homepage adverts
		 */
		for ($i = 0; $i < 10; $i++)
		{
			/**
			 * Get random image from loremflickr
			 */
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://loremflickr.com/800/200');
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Must be set to true so that PHP follows any "Location:" header
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$a   = curl_exec($ch); // $a will contain all headers
			$url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); // This is what you need, it will return you the last effective URL

			$info = pathinfo($url);
			$contents = file_get_contents($url);
			$file = '/tmp/' . $info['basename'];
			file_put_contents($file, $contents);
			$uploaded_file = new UploadedFile($file, $info['basename']);
			$path = $uploaded_file->store('hpadvert_seed');

			$advert                = new Advert();
			$advert->active        = true;
			$advert->advertiser_id = 1;

			$advertable             = new HomePageAdvert();
			$advertable->image_path = $path;
			$advertable->links_to   = $url;

			DB::transaction(function () use ($advert, $advertable)
			{
				$advertable->save();
				$advert->advertable()->associate($advertable);
				$advert->save();
			});
		}
	}
}
