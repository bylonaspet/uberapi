<?php

namespace FUber;

require_once __DIR__ . '/vendor/autoload.php';

use Stevenmaguire\Uber\Client;
use GuzzleHttp\Client as GuzzleClient;

header('Content-Type: application/json; charset=utf-8');
//header('Content-Type: text/html; charset=utf-8');

$fail = function ($reason, $code) {
	http_response_code((int) $code);
	echo \json_encode((object) ['error' => (string) $reason], JSON_UNESCAPED_UNICODE);
};

$requiredQueryArgs = [
  'ride_prices',
];

foreach ($requiredQueryArgs as $arg) {
	if (!array_key_exists($arg, $_GET)) {
		return $fail(sprintf('Missing parameter %s', $arg), 404);
	}
}

$accessToken = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJzY29wZXMiOlsicHJvZmlsZSIsImhpc3RvcnkiLCJwbGFjZXMiLCJoaXN0b3J5X2xpdGUiXSwic3ViIjoiZTIyNWNiOGEtNDJhNy00YWI2LWExZGQtYzZmNzI4ZmMwZmViIiwiaXNzIjoidWJlci11czEiLCJqdGkiOiJjZGVkODk5Zi03YjU1LTRmZTUtODYxNy03OWZlMTM1NGUyZmUiLCJleHAiOjE0NzkxMjIxNjYsImlhdCI6MTQ3NjUzMDE2NSwidWFjdCI6InRObUhpZ0NWbmVnWGswOEZ5cHcydkJaa3JGTXVDTyIsIm5iZiI6MTQ3NjUzMDA3NSwiYXVkIjoiLXhJeTVfR2taQUdVWU9XQWs5QzItVV9jNHB2WjF1U2sifQ.C8B2lkAfeF8Ty1mVK_9LHao23UVTmnvIoODAa01E0NP9XSulpga0y5kYmffGoTnnYOWJgQoAC9YgZIlxscm1FlMjB1CmzKBPyKXpd666AFUo3Qj3NC3-vgM_Lia-w7ifD683nUIjkYer-ns-NsNIwR5lpKn8hYuTi-GG7q_IkMYSN5rS4IPmN2Z886W_5NB9saMQl6alVOqBAhzTiCH2wNx8BmX58NkYD-JOa67e0-kcD1DgcBJHq2O1kf56DD7oipdDAT1DgaHK1xYpxFyiDvujo_HG8eXUEaUxCLgYP5dIkT7k2u5MYxO2f9OFca7u5nznl6fEIP6ft7BLqnTjfA';
$client = new Client([
	'access_token' => $accessToken,
	'server_token' => 'bWyJdHPZOtS3sIJq80Nh-0X-V3pS2YzMO27k04Ks',
	'use_sandbox' => true,
	'version' => 'v1',
	'locale' => 'en_US',
]);
$client->setHttpClient($guzzle = new GuzzleClient(['defaults' => ['verify' => false]]));
$client->setVersion('v1.2');

$requestIds = [];
try {
	// $history = $client->getHistory(['limit' => 50]);
	//foreach ($history->history as $ride) {
	//	$requestIds[] = $ride->request_id;
	//}
	$requestIds = range(0, 9);

} catch (\Exception $e) {
	return $fail($e->getMessage(), 500);
}

$fakeRequestReceipt = [
	"request_id" => "b5512127-a134-4bf4-b1ba-fe9f48f56d9d",
	"charges" => [
		[
			"name" => "Base Fare",
			"amount" => "2.20",
			"type" => "base_fare"
		],
		[
			"name" => "Distance",
			"amount" => "2.75",
			"type" => "distance"
		],
		[
			"name" => "Time",
			"amount" => "3.57",
			"type" => "time"
		]
	],
	"surge_charge" => [
		"name" => "Surge x1.5",
		"amount" => "4.26",
		"type" => "surge"
	],
	"charge_adjustments" => [
		[
			"name" => "Promotion",
			"amount" => "-2.43",
			"type" => "promotion"
		],
		[
			"name" => "Booking Fee",
			"amount" => "1.00",
			"type" => "booking_fee"
		],
		[
			"name" => "Rounding Down",
			"amount" => "0.78",
			"type" => "rounding_down"
		],
	],
	"normal_fare" => "$8.52",
	"subtotal" => "$12.78",
	"total_charged" => "$5.92",
	"total_owed" => null,
	"total_fare" => "$5.92",
	"currency_code" => "USD",
	"duration" => "00=>11=>35",
	"distance" => "1.49",
	"distance_label" => "miles"
];

$response = [];
foreach ($requestIds as $requestId) {
	echo \json_encode([
		'trip_length' => '6.5km',
		'start_time' => '15.10.2016 03:44 AM',
		'end_time' => '15.10.2016 03:53 AM',
		'map_link' => 'https://imgur.com/ovc4UaU',
	]);
	return;
}

return $fail('fail', 404);
