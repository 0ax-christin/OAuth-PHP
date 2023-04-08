<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
$url = "http://192.168.199.22/receiver.php";
$curl = curl_init();

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$_POST["password"];
		$SHA256pass = hash('sha256', $_POST["password"]);
		$credentials = array(
			'username' => $_POST["username"],
			'password' => $SHA256pass
		);

		$curlConfig = array(
			CURLOPT_URL => $url,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => json_encode($credentials)
		);
		curl_setopt_array($curl, $curlConfig);
		$response = json_decode(curl_exec($curl));
		//$response = curl_exec($curl);
		curl_close($curl);

		$JSONBody_Encrypted_Base64 = $response->JSONBody;
		$JSON_Body_EncryptedwithIV = base64_decode($JSONBody_Encrypted_Base64);
		$IV = substr($JSON_Body_EncryptedwithIV, 0, 16);
		$JSON_Body_Encrypted = substr($JSON_Body_EncryptedwithIV, 16);
		$JSON_Body_OAuth = openssl_decrypt($JSON_Body_Encrypted, 'AES-256-CBC', $SHA256pass, OPENSSL_RAW_DATA, $IV);
		
		$OAuth_JSON =json_encode(json_decode($JSON_Body_OAuth));
		//echo $OAuth_JSON;
		//$keyVal = explode(",", trim($OAuth_JSON, "{}"));
		//$token = explode(":", $keyVal[1])[1];
		//$OAuth_JSON_obj = json_decode($OAuth_JSON, true);
		//var_dump($OAuth_JSON_obj);
		//$JSON_Body_to_App = array(
		//	'OAuthtoken' => $OAuth_JSON_obj->token
		//);
		//print_r($JSON_Body_to_App);
		//print_r(json_decode(json_encode($JSON_Body_to_App)));

		$urlApplication = "http://192.168.194.207/auth/auth.php";

		$curl_to_Application = curl_init();
		//echo $token . "<br>";
		$curlConfigApplication = array (
			CURLOPT_URL => $urlApplication,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $OAuth_JSON//$token
		);
		curl_setopt_array($curl_to_Application, $curlConfigApplication);
		$responseApplication = curl_exec($curl_to_Application);
		//if ($responseApplication == false) {
		//	echo $curl_error($curl_to_Application);
		//	$error_code = $curl_error($curl_to_Application);
		//}
		//echo $responseApplication;
		curl_close($curl_to_Application);
		echo $responseApplication;
	}
?>
<head>
	<title>Client credentials</title>
</head>

<body>
	
	<h1>ENTER YOUR CREDENTIALSS</h1>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
		<label for="username">Username</label>
		<input type="text" name="username" id="username">

		<label for="password">Password</label>
		<input type="text" name="password" id="password">
	
		<input type="submit" name="SUBMIT">
	</form>
</body>
