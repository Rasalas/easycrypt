<?php

include('libs/class.easycrypt.php');

$ez = new EasyCrypt('supersecurepassphrase', 'saltandpepper');

$data = ['user_id'=>'12','firstname'=>'John', 'lastname'=>'Doe'];

$crypt = $ez->encrypt($data);
$crypt_urlsafe = $ez->encrypt($data,URL_SAFE);

echo '<h3>Original data:</h3>';
json_view($data);
echo '<br>';
echo '<h3>ENCRYPTED:</h3>';
echo '<textarea rows=4 style="width:100%">'.$crypt.'</textarea>';

echo '<h3>DECRYPTED:</h3>';
json_view($ez->decrypt($crypt),JSON_PRETTY_PRINT);


echo '<br>';
echo '<h3>ENCRYPTED (url safe):</h3>';
echo '<textarea rows=4 style="width:100%">'.$crypt_urlsafe.'</textarea>';
echo '<br>';
echo '<h3>DECRYPTED (url safe):</h3>';
json_view($ez->decrypt($crypt_urlsafe,URL_SAFE));

echo '<br>';

function json_view($array){
    $rows = count($array) + 2;
    echo '<textarea rows='.$rows.' style="width:100%">'.json_encode($array,JSON_PRETTY_PRINT).'</textarea>';
}

