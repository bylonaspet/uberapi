<?php

namespace FKolonial;

require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;

const KOLONIAL_API = 'https://www.kolonial.cz/api/v2';

header('Content-Type: application/json; charset=utf-8');

$fail = function ($reason, $code) {
	http_response_code((int) $code);
	echo \json_encode((object) ['error' => (string) $reason], JSON_UNESCAPED_UNICODE);
};

$requiredQueryArgs = [
	'client_id',
	'client_secret',
	'username',
	'password',
	'variable_symbol',
];

foreach ($requiredQueryArgs as $arg) {
	if (!array_key_exists($arg, $_GET)) {
		return $fail(sprintf('Missing parameter %s', $arg), 404);
	}
}
echo "params ok";


$client = new \Stevenmaguire\Uber\Client(array(
    'access_token' => 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJzY29wZXMiOltdLCJzdWIiOiJlMjI1Y2I4YS00MmE3LTRhYjYtYTFkZC1jNmY3MjhmYzBmZWIiLCJpc3MiOiJ1YmVyLXVzMSIsImp0aSI6ImRjOTFmNzIxLTJkYWItNGY3MS1iNDM3LTY1ZmM4YTYyNjcyMSIsImV4cCI6MTQ3OTExNjMzNywiaWF0IjoxNDc2NTI0MzM2LCJ1YWN0Ijoic1RDWWJ6elpYWVdCN090UEREaGNvSUR5UFJVczFOIiwibmJmIjoxNDc2NTI0MjQ2LCJhdWQiOiIteEl5NV9Ha1pBR1VZT1dBazlDMi1VX2M0cHZaMXVTayJ9.MgzHM-bF-6UWTZQrtp8NZHJAfE0V-quXXPuvsQ_G-STMBmm937IQ6sF7Q75gApmPjuqqhTMVCjjKCYyFO0Twz-EWuJBEwpK8hIN0wsbc6FRFPlUsUNL2EMWQ0wZk6WJWwFXQJBbQl7uelclROOMCS9ZEFfnRT0EYl6niPWui1xiGq9rP9YQYMPbwLZZFzFm-kz3vBkk-r2wLTxUKd8pCGNxszqtKsr_wt-fq8yujVFbVnxkaoUt25tZmIWYGQ37s_lrkm9173yap517bfUSMFfLWhqqW8kHum5JXTmXD7eDeDYmjQ5Kk1krIc4i30jjX5_p11U-K168aIKkIxwnylQ',
    'server_token' => 'bWyJdHPZOtS3sIJq80Nh-0X-V3pS2YzMO27k04Ks',
    'use_sandbox'  => true, // optional, default false
    'version'      => 'v1', // optional, default 'v1'
    'locale'       => 'en_US', // optional, default 'en_US'
));

echo "uber ok";

$guzzle = new Client(['verify' => false]);

try {
	$response = $guzzle->post(KOLONIAL_API . '/authorize', [
		'json' => [
			'grant_type' => 'password',
			'client_id' => $_GET['client_id'],
			'client_secret' => $_GET['client_secret'],
			'username' => $_GET['username'],
			'password' => $_GET['password'],
		],
	]);
} catch (ServerException $e) {
	return $fail($e->getMessage(), 500);
}

$accessToken = \json_decode($response->getBody()->getContents())->access_token;

try {
	$response = $guzzle->get(KOLONIAL_API . '/orders', [
		'headers' => [
			'Authorization' => 'Bearer ' . $accessToken,
		],
	]);
} catch (ServerException $e) {
	return $fail($e->getMessage(), 500);
}

$orders = \json_decode($response->getBody()->getContents())->orders;

foreach ($orders as $order) {
	if ($order->number == $_GET['variable_symbol']) {
		$products = [];
		foreach ($order->products as $product) {
			if (!array_key_exists($product->product->id, $products)) {
				$products[$product->product->id] = [
					'id' => $product->product->id,
					'name' => $product->product->name,
					'image' => array_shift($product->product->images),
					'quantity' => $product->quantity,
					'unit' => $product->unit,
				];
			} else {
				$products[$product->product->id]['quantity'] += $product->quantity;
			}
		}
		echo \json_encode(array_values($products), JSON_UNESCAPED_UNICODE);
		return;
	}
}

return $fail('No order found', 404);
