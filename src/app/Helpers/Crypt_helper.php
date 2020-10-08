<?php

function encrypt($json)
{
    $key = pack('H*', '7824262c7bc1df38dcc1ea799faff39fa17d52453ce65040a7280c9141b60267');
    $iv = pack('H*', 'f1cd03bba534cd7039708a30d7cd722d');
    return json_encode(array(
        'payload' => openssl_encrypt(json_encode($json), 'aes-256-cbc', $key, 0, $iv)
    ));
}

function decrypt($ct)
{
    $key = pack('H*', '7824262c7bc1df38dcc1ea799faff39fa17d52453ce65040a7280c9141b60267');
    $iv = pack('H*', 'f1cd03bba534cd7039708a30d7cd722d');
    return (array) json_decode(openssl_decrypt($ct, 'aes-256-cbc', $key, 0, $iv));
}

?>