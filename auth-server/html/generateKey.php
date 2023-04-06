<?php
	$secretKey = sodium_crypto_secretbox_keygen();
	$secretKeyHex = sodium_bin2hex($secretKey);
	$nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

	echo "Current Secret key: " . $secretKey . "<br>";
	echo "Secret Key hex: " . $secretKeyHex . "<br>";
	echo "Nonce: " . $nonce . "<br>";

	file_put_contents(__DIR__ . "/keys/secret.key", $secretKey);
	file_put_contents(__DIR__ . "/hex/secret.key.hex", $secretKeyHex);
	
?>
