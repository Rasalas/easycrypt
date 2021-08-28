<?php

namespace Rasalas\Tools;

define('URL_SAFE', true);

/**
 * EasyCrypt_Class is a class to easily encrypt data with a simple passphrase
 * 
 * EasyCrypt_Class is a class to encrypt and decrypt array data, which 
 * you can append to your URL. If someone tampers with it, you'll notice.
 * DO NOT use this to store user data or other sensitive data.
 * Mainly use it for short term use - e.g. to encrypt a user_id in a GET request
 * 
 * Example usage:
 * $data = ['user_id'=>'12','firstname'=>'John', 'lastname'=>'Doe'];
 * 
 * $ez = new Rasalas\Tools\EasyCrypt('supersecurepassphrase', 'saltandpepper');
 * $crypt = $ez->encrypt($data);
 * 
 * echo $ez->decrypt($crypt);
 * 
 * @package EasyCrypt
 * @author  Torben Buck <mail@tbuck.de>
 * @version 1.0.0
 * @access  public
 * @see     https://www.tbuck.de/example/easycrypt
 */
class EasyCrypt
{
    private const ENCRYPTION_ALGORITHM = 'AES-128-ECB';
    private const DELIMITER = '----';
    private $key = '';
    private $salt = '';

    /**
     * Create a new instance of EasyCrypt
     *
     * @param  String $key to encrypt/decrypt the data
     * @param  String $salt to sign the data
     * @return void
     */
    public function __construct($key, $salt)
    {
        $this->$key = $key;
        $this->$salt = $salt;
    }

    /**
     * encrypt data
     *
     * @param  array|String $data
     * @param  bool $url_safe use URL_SAFE = true for a String without special chars (longer)
     * @return String encrypted string 
     */
    public function encrypt($data, bool $url_safe = false)
    {
        $data_arr = [];

        if (!is_array($data)) {
            $data_arr[self::DELIMITER . uniqid() . self::DELIMITER] = $data;
        } else {
            $data_arr = $data;
        }

        $data_arr = serialize($this->sign_data($data_arr));

        $encrypted = openssl_encrypt($data_arr, self::ENCRYPTION_ALGORITHM, $this->key);

        if ($url_safe) return rawurlencode(base64_encode($encrypted));

        return $encrypted;
    }

    /**
     * decrypt encrypted string
     *
     * @param  String $string
     * @param  bool $url_safe use URL_SAFE = true if it was encrypted using URL_SAFE (this is faster but works without it)
     * @return array|String depending on what type of data it got
     */
    public function decrypt(String $string, bool $url_safe = false)
    {

        $data = unserialize(openssl_decrypt($string, self::ENCRYPTION_ALGORITHM, $this->key));

        if (!is_array($data) || $url_safe) {
            $data = base64_decode(rawurldecode($string));
            $data = unserialize(openssl_decrypt($data, self::ENCRYPTION_ALGORITHM, $this->key));
        }

        if (is_array($data) && $this->check_signature($data)) {
            $data = $this->strip_signature($data);
            $keys = array_keys($data);
            if (count($data) == 1 && $this->surroundedBy($keys[0], self::DELIMITER)) {
                return $data[$keys[0]];
            }
            return $data;
        }
        return false;
    }

    /**
     * sign_data hashes the contents of the array and adds a signature to the array
     *
     * @param  array $data
     * @return array with signature
     */
    private function sign_data(array $data): array
    {
        $salted_array = array_merge($data, ['salt' => $this->salt]);
        $signature = md5(serialize($salted_array));

        $signed_data = array_merge($data, ['signature' => $signature]);
        return $signed_data;
    }

    /**
     * check_signature checks if the signature is equal to the hash of the array
     *
     * @param  array $data
     * @return bool
     */
    private function check_signature(array $data): bool
    {
        if (!isset($data['signature'])) return false;

        $data_signature = $data['signature'];
        unset($data['signature']);

        $salted_array = array_merge($data, ['salt' => $this->salt]);
        $temp_signature = md5(serialize($salted_array));

        if ($data_signature === $temp_signature) return true;

        return false;
    }

    /**
     * strip_signature strips the array of the appended signature
     *
     * @param  array $data
     * @return array
     */
    private function strip_signature(array $data): array
    {
        unset($data['signature']);
        return $data;
    }

    // UTIL    
    /**
     * surroundedBy checks if a string starts and ends with a specific delimiter
     *
     * @param  String $string
     * @param  String $delimiter
     * @return bool
     */
    private function surroundedBy(String $string,String $delimiter): bool
    {
        return $this->startsWith($string, $delimiter) && $this->endsWith($string, $delimiter);
    }

    /**
     * startsWith tells if a string starts with a string
     *
     * @param  String $haystack
     * @param  String $needle
     * @return bool
     */
    private function startsWith(String $haystack, String $needle): bool
    {
        $length = strlen($needle);
        return substr($haystack, 0, $length) === $needle;
    }

    /**
     * endsWith tells if a string ends with a string
     *
     * @param  String $haystack
     * @param  String $needle
     * @return bool
     */
    private function endsWith(String $haystack, String $needle): bool
    {
        $length = strlen($needle);
        if (!$length) return true;
        return substr($haystack, -$length) === $needle;
    }
}
