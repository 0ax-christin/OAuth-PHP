<?php
error_reporting(E_ALL);
$url = "http://192.168.197.100/myauth/token.php";

$curl = curl_init();
//check the data

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//get data from string
	$username = $_POST['username'];
	$password = $_POST['password'];

	$data = array(
	 	'param1' => $username,
	 	'param2' => $password
	);
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
		//$nonce = randombytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
		//$secretKey = file_get_contents("keys/secret.key", true);
		//$encryptedAuth = sodium_crypto_secretbox($unencryptedAuth, $nonce, $secretKey);
		//$auth_send_token = sodium_bin2base64($nonce . $encryptedAuth, SODIUM_BASE64_VARIANT_ORIGINAL);
		$data_success = array(
			'auth' => 'success',
			'token' => $unencryptedAuth  
		);
		echo json_encode($data_success);
		//echo $response->auth_token;
	}
}
?>
