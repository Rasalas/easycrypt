# EasyCrypt
EasyCrypt helps to easily encrypt data with a simple passphrase.

## What is it for?
It encrypts array data or strings that you might want to append to your URL as GET data. Or maybe you want to put it in a hidden input. All yours to decide.

If someone tampered with the data, you'll notice, as it's going to return `false` instead of with data (this might change). That's also what's the `$salt` is for. EasyCrypt signs the data and checks if the data is still unchanged.

Please DO NOT use this to store user data or other sensitive data.
## Installation
```console
rasa@home:~$ composer require rasalas/easycrypt
```
## Example usage
```php
<?php
// import
require __DIR__ . '/vendor/autoload.php'; // or include('vendor/rasalas/easycrypt/src/class.easycrypt.php');
$ez = new Rasalas\Tools\EasyCrypt('supersecurepassphrase', 'saltandpepper');

// test data
$data = ['user_id'=>'12','firstname'=>'John', 'lastname'=>'Doe'];

// encrypt
$crypt = $ez->encrypt($data);
 
// decrypt
$decrypted = $ez->decrypt($crypt);

// print the array in a human readable format
echo '<pre>' . json_encode($decrypted, JSON_PRETTY_PRINT) . '</pre>';
```
#### crypt (216 bytes)
`P9P9WtCGQYQu+Isn7uO0HAMx4Cg+NcFKGmB5Abg4vJD00+PQXmEvHBvycapbOLKZOYnHw0L2Omv/61/6NLWJbQKfjQLXlPykGWiTjwGpz1LD0pZTJla8euIbJHhFiu5RByqeF8Dh40I25Nwh3vGBdIbgffqgT8qIkbIqFQrif8BTzFy1mcLa5so/hWcyUx6T9d8Wrw7l7ifIL2MzReS0hA==`

### Output
```json
{
    "user_id": "12",
    "firstname": "John",
    "lastname": "Doe"
}
```

## URL safe usage
If you want to use it in URLs or as html-input value, you probably dislike characters like `/`. So just use the `URL_SAFE` constant, (`true` and `1`, should work too, but stick to the former).

This makes the crypt strings longer. So pick what's best.

`::decrypt()` can decrypt an url safe crypt without the `URL_SAFE` parameter, but has to try to decrypt the data first; so give it a break and use it. 😉
```php
// encrypt
$crypt = $ez->encrypt($data, URL_SAFE);
 
// decrypt
$decrypted = $ez->decrypt($crypt, URL_SAFE);

```
#### crypt (288 bytes, 33% bigger)
`UDlQOVd0Q0dRWVF1K0lzbjd1TzBIQU14NENnK05jRktHbUI1QWJnNHZKRDAwK1BRWG1FdkhCdnljYXBiT0xLWk9Zbkh3MEwyT212LzYxLzZOTFdKYlFLZmpRTFhsUHlrR1dpVGp3R3B6MUxEMHBaVEpsYThldUliSkhoRml1NVJCeXFlRjhEaDQwSTI1TndoM3ZHQmRJYmdmZnFnVDhxSWtiSXFGUXJpZjhCVHpGeTFtY0xhNXNvL2hXY3lVeDZUOWQ4V3J3N2w3aWZJTDJNelJlUzBoQT09`
