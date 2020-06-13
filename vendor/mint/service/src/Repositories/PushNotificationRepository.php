<?php
namespace Mint\Service\Repositories;

class PushNotificationRepository
{

	/**
	 * Send notification
	 * @param  array  $params
	 * @return void
	 */
	public function send($params = array())
	{
        $url = config('app.push').'/api/send';

		try {
			$data = [
				'title'      => gv($params, 'title'),
				'body'       => gv($params, 'body'),
				'tokens'     => gv($params, 'tokens', []),
				'url'        => url()->current(),
				'item'       => config('app.item'),
				'auth_token' => config('config.pusher_auth_token')
	        ];

	        $client = new \GuzzleHttp\Client();
	        $result = $client->post($url, [
	        	'form_params' => $data
	        ]);

		} catch ( \Exception $exception ) {}
	}
}