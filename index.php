<?php

include('libs/class.easycrypt.php');

use Rasalas\Tools\EasyCrypt as EasyCrypt;

$ez = new EasyCrypt('supersecurepassphrase', 'saltandpepper');

$data = ['user_id' => '12', 'firstname' => 'John', 'lastname' => 'Doe'];

$crypt = $ez->encrypt($data);
$crypt_urlsafe = $ez->encrypt($data, URL_SAFE);

$decryped = $ez->decrypt($crypt);
$decryped_urlsafe = $ez->decrypt($crypt_urlsafe, URL_SAFE);

function textarea_view($data)
{

    if (is_array($data)) {
        $body = json_encode($data, JSON_PRETTY_PRINT);
        $rows = count($data) + 2;
    } else {
        $body = $data;
        $rows = 6;
    }

    echo '<textarea rows=' . $rows . ' style="width:100%">' . $body . '</textarea>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Example</title>
</head>

<body>
    <style>
        body {
            margin: 0 auto;
            padding: 0 10%;
            max-width: 710px;
            color: #000;
            background-color: #fff;
        }

        html {
            -webkit-text-size-adjust: 100%;
        }

        h3 {
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            margin-bottom: 0.3rem;
        }

        .result {
            margin-left: 1.5rem;
            padding-left: 1.5rem;
            margin-top: 3rem;
            margin-bottom: 3rem;
            border-left: 0.3rem solid #18659c;
        }

        small{
            font-size: 0.8rem;
            color: #18659c;
        }
    </style>
    <h3>ORIGINAL DATA</h3>
    <?= textarea_view($data) ?>
    <div class="result">
        <h3>ENCRYPTED <small><?= var_export( strlen($crypt))?> bytes</small></h3>
        <?= textarea_view($crypt) ?>
        
        <h3>DECRYPTED</h3>
        <?= textarea_view($decryped) ?>
    </div>
    <div class="result">
        <h3>ENCRYPTED (url safe) <small><?= var_export( strlen($crypt_urlsafe))?> bytes</small></h3>
        <?= textarea_view($crypt_urlsafe) ?>
        <h3>DECRYPTED (url safe)</h3>
        <?= textarea_view($decryped_urlsafe) ?>
    </div>
</body>

</html>