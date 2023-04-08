<?php
error_reporting(E_ALL);
$url = "http://192.168.197.100/myauth/token.php";

$curl = curl_init();
//check the data

//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//get data from string
	//$username = $_POST['username'];
	//$password = $_POST['password'];

	$clientPOSTRequest = json_decode(file_get_contents('php://input'));

	$username = $clientPOSTRequest->username;
	$password = $clientPOSTRequest->password;
	//echo $password;
	$curlConfig = array(
		CURLOPT_URL => $url,
		CURLOPT_POST => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_USERPWD => "$username:$password",
		CURLOPT_POSTFIELDS => array(
			'grant_type' => 'client_credentials' 
		)
	);
	curl_setopt_array($curl, $curlConfig);
	$response = json_decode(curl_exec($curl));
	curl_close($curl);
	if(isset($response->error)) {
		$data_fail = array(
			'auth' => 'fail',
			'token' => ''
		);
		echo json_encode($data_fail);
	} else {
		$unencryptedAuth = $response->access_token;
		$nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
		$secretKey = file_get_contents("keys/secret.key", true);
		$encryptedAuth = sodium_crypto_secretbox($unencryptedAuth, $nonce, $secretKey);
		$auth_send_token = sodium_bin2base64($nonce . $encryptedAuth, SODIUM_BASE64_VARIANT_ORIGINAL);
		$data_success = array(
			'auth' => 'success',
			'token' => $auth_send_token //$unencryptedAuth  
		);
		//echo json_encode($data_success);

		$client_raw_response = json_encode($data_success);
		//echo $client_raw_response;
		$iv = random_bytes(16);
		$encrypted_client_response = openssl_encrypt($client_raw_response, "aes-256-cbc", $password,  OPENSSL_RAW_DATA, $iv);
		$combined_data = $iv . $encrypted_client_response;
		//echo "ENC: " . $combined_data . "<br>";
		$base64_encrypted_client_resp = base64_encode($combined_data);
		//echo "Base64 ENC: " . $base64_encrypted_client_resp . "<br>";
		//$base64_encrypted_client_resp = sodium_bin2base64($nonceSHA256 . $encrypted_client_response, SODIUM_BASE64_VARIANT_ORIGINAL);
		$data_SHA256 = array(
			'JSONBody' => $base64_encrypted_client_resp	
		);
		echo json_encode($data_SHA256);
	}
//}
?>
