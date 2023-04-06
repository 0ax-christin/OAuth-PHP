<?php
    // Load Bob's public key from a database, etc.
    $bobPublicKey = '...';
    $message = "Hi Bob, this is an anonymous secret message!";
    $encrypted = sodium_crypto_box_seal($message, $bobPublicKey);
    sodium_memzero($message);    
    // Again, for transmission, we base64 encode the ciphertext.
    $result = sodium_bin2base64($encrypted, SODIUM_BASE64_VARIANT_ORIGINAL);
?>
