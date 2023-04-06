<?php
    // Load up the hex key we saved earlier, using file_get_contents() to read the file or whatever.
    $secretKeyHex = file_get_contents('/path/to/key');
    // Convert the hex key to a binary key using sodium_hex2bin().
    $secretKey = sodium_hex2bin($secretKeyHex);
    // Grab the base64 encoded message from the database or wherever.
    $encrypted = file_get_contents('/path/to/message');

    // Convert the base64 encoded message to binary using sodium_base642bin().
    $ciphertext = sodium_base642bin($encrypted, SODIUM_BASE64_VARIANT_ORIGINAL);

    // Now we need to extract the nonce from the beginning of the message.
    // We simply take the first 24 bytes of the message.
    $nonce = mb_substr($ciphertext, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
    // And the message is the rest of the ciphertext.
    $ciphertext = mb_substr($ciphertext, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

    // Now we can decrypt the message with the secret key and nonce.
    $plaintext = sodium_crypto_secretbox_open($ciphertext, $nonce, $secretKey);

    // If the plaintext is false, it means the message was corrupted.
    if ($plaintext === false) {
        die('Could not decrypt');
    }

    // Now we overwrite the nonce and secret key with null bytes in memory, to prevent any leakage of sensitive data.
    sodium_memzero($nonce);
    sodium_memzero($secretKey);
    sodium_memzero($secretKeyHex);
    sodium_memzero($ciphertext);

    // Finally, we can output the plaintext.
    echo $plaintext, PHP_EOL;    
