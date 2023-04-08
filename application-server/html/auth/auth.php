<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	$clientRequest = json_decode(file_get_contents("php://input"));
	$token = $clientRequest->token;
	$unencryptedToken = $clientRequest->ogToken;
	$encryptedTokenwithIV = base64_decode($token);
	$iv = substr($encryptedTokenwithIV, 0, 16);
	$encryptedToken = substr($encryptedTokenwithIV, 16);

	$secretKey = "OAuthHell";

	$decrypt_token = openssl_decrypt($encryptedToken, "AES-256-CBC", $secretKey, OPENSSL_RAW_DATA, $iv);


	echo "Decrypted token: " . $decrypt_token . "<br>";
	echo "Original token for verification: " . $unencryptedToken . "<br>";
	if($decrypt_token === false) {
		echo "error not decrypted";	
	} else {	
		if($decrypt_token === $unencryptedToken) {
			echo "RS{OAuth_is_hell}";
		}
	}
	//	echo "RS{OAuth_is_hell}";
	//} else {
	//	echo "Failure";
	//}

?>
