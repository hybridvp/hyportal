<?php
// Encrypting

function pinencrypt($data_input){     
 
    $key = "key1";
    $td = mcrypt_module_open('cast-256', '', 'ecb', '');
    $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    mcrypt_generic_init($td, $key, $iv);
    $encrypted_data = mcrypt_generic($td, $data_input);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    $encoded_64=base64_encode($encrypted_data);
    return $encoded_64;
}   
// Decrypting

function pindecrypt($encoded_64){
    $decoded_64=base64_decode($encoded_64);
    $key = "key1";// same as you used to encrypt
    $td = mcrypt_module_open('cast-256', '', 'ecb', '');
    $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    mcrypt_generic_init($td, $key, $iv);
    $decrypted_data = mdecrypt_generic($td, $decoded_64);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    return $decrypted_data;
}
 
//$encrypted = encrypt($stuff, $key);
//$decrypted = decrypt($encrypted, $key);
 
//echo "Encrypted is ".$encrypted . nl();
//echo "Decrypted is ".$decrypted . nl();

?>