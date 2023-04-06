<?php
	$ClientKeyPair = sodium_crypto_box_keypair();
	$ClientPublicKey = sodium_crypto_box_publickey($ClientKeyPair);
	$ClientPrivateKey = sodium_crypto_box_secretkey($ClientKeyPair);
	
	echo "Public Key: " . $ClientPublicKey  . "<br> <br>";
	echo "Private Key: " . $ClientPrivateKey . "<br>";
	file_put_contents("keypairs/auth-keypair.key", $ClientKeyPair);
	file_put_contents("publicKeys/auth-public.key", $ClientPublicKey);
	file_put_contents("privateKeys/auth-private.key", $ClientPrivateKey);
		
?>
