<?php
	$message = "Hey";
	$nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
	
	$secretkey = file_get_contents("keys/secret.key", true);
	echo "Secret key: " . $secretkey . "<br>";
	$ciphertext = sodium_crypto_secretbox($message, $nonce, $secretkey);

	echo "Cipher text:" . $ciphertext . "<br>";

	$result = sodium_bin2base64($nonce . $ciphertext, SODIUM_BASE64_VARIANT_ORIGINAL);

	echo "Result: " . $result . "<br>";

?>
