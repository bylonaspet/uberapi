<?php

namespace FUber;

require_once __DIR__ . '/vendor/autoload.php';

use Stevenmaguire\Uber\Client;
use GuzzleHttp\Client as GuzzleClient;

const UBER_API = '';

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

$client = new Client([
	'access_token' => 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJzY29wZXMiOltdLCJzdWIiOiJlMjI1Y2I4YS00MmE3LTRhYjYtYTFkZC1jNmY3MjhmYzBmZWIiLCJpc3MiOiJ1YmVyLXVzMSIsImp0aSI6ImRjOTFmNzIxLTJkYWItNGY3MS1iNDM3LTY1ZmM4YTYyNjcyMSIsImV4cCI6MTQ3OTExNjMzNywiaWF0IjoxNDc2NTI0MzM2LCJ1YWN0Ijoic1RDWWJ6elpYWVdCN090UEREaGNvSUR5UFJVczFOIiwibmJmIjoxNDc2NTI0MjQ2LCJhdWQiOiIteEl5NV9Ha1pBR1VZT1dBazlDMi1VX2M0cHZaMXVTayJ9.MgzHM-bF-6UWTZQrtp8NZHJAfE0V-quXXPuvsQ_G-STMBmm937IQ6sF7Q75gApmPjuqqhTMVCjjKCYyFO0Twz-EWuJBEwpK8hIN0wsbc6FRFPlUsUNL2EMWQ0wZk6WJWwFXQJBbQl7uelclROOMCS9ZEFfnRT0EYl6niPWui1xiGq9rP9YQYMPbwLZZFzFm-kz3vBkk-r2wLTxUKd8pCGNxszqtKsr_wt-fq8yujVFbVnxkaoUt25tZmIWYGQ37s_lrkm9173yap517bfUSMFfLWhqqW8kHum5JXTmXD7eDeDYmjQ5Kk1krIc4i30jjX5_p11U-K168aIKkIxwnylQ',
	'server_token' => 'bWyJdHPZOtS3sIJq80Nh-0X-V3pS2YzMO27k04Ks',
	'use_sandbox' => true,
	'version' => 'v1',
	'locale' => 'en_US',
]);
$client->setHttpClient(new GuzzleClient(['defaults' => ['verify' => false]]));
$client->setVersion('v1.2');

try {
	$history = $client->getHistory([]);
	var_dump($history);
	die;

} catch (\Exception $e) {
	$fail($e->getMessage(), 500);
}

return $fail('fail', 404);
