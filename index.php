<?php

include('libs/class.encrypt.php');

$ez = new EasyCrypt('supersecurepassphrase', 'saltandpepper');

$data = ['user_id'=>'12','firstname'=>'John', 'lastname'=>'Doe'];

$crypt = $ez->encrypt($data);


var_dump($data) ;
echo '<br>';
echo $crypt;
echo '<br>';
echo strlen($crypt);
echo '<br>';
var_dump($ez->decrypt($crypt));

