<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	$clientRequest = json_decode(file_get_contents("php://input"));
	$token = $clientRequest->token;
	$secretKey = file_get_contents("keys/secret.key");
	$encryptedTokenwithNonce = sodium_base642bin($token, SODIUM_BASE64_VARIANT_ORIGINAL);
	$nonce = mb_substr($encryptedTokenwithNonce, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
	$encryptedToken = mb_substr($token, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

	$OAuthToken = sodium_crypto_secretbox_open($token, $nonce, $secretKey);
	var_dump($OAuthToken);
	//if(isset($OAuthToken)) {
	//	echo "RS{OAuth_is_hell}";
	//} else {
	//	echo "Failure";
	//}

?>
