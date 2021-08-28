<?php

include('libs/class.encrypt.php');

$ezcrypt = new SimpleEncrypt('supersicherespasswort', 'salzundpfeffer');
$data = ['id'=>'12','name'=>'Björn Dør Wilhelm'];
$crypt = $ezcrypt->encrypt($data,URL_SAFE);
var_dump($data) ;
echo '<br>';
echo $ezcrypt->encrypt($data);
echo '<br>';
echo $ezcrypt->encrypt($data,URL_SAFE);
echo '<br>';
var_dump($ezcrypt->decrypt($crypt,URL_SAFE));

