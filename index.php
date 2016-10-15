<?php

namespace FUber;

require_once __DIR__ . '/vendor/autoload.php';

use Stevenmaguire\Uber\Client;
use GuzzleHttp\Client as GuzzleClient;

header('Content-Type: application/json; charset=utf-8');

$fail = function ($reason, $code) {
	http_response_code((int) $code);
	echo \json_encode((object) ['error' => (string) $reason], JSON_UNESCAPED_UNICODE);
};

$requiredQueryArgs = [

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
	$history = $client->getHistory(['limit' => 50]);
	foreach ($history->history as $ride) {
		$requestIds[] = $ride->request_id;
	}

} catch (\Exception $e) {
	return $fail($e->getMessage(), 500);
}

foreach ($requestIds as $requestId) {
	try {
		$response = $guzzle->get('https://sandbox-api.uber.com/v1/requests/' . $requestId . '/receipt', [
			'headers' => [
				'Authorization' => 'Bearer ' . $accessToken,
				'Accept-Language' => 'en_US',
			],
		]);
		echo $response->getBody();
		return;

	} catch (\Exception $e) {
		return $fail($e->getMessage(), 500);
	}
}

return $fail('fail', 404);
