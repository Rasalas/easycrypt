<?php

class EasyCrypt
{
    private const ENCRYPTION_ALGORITHM = 'AES-128-ECB';
    private const DELIMITER = '----';
    private $key = '';
    private $salt = '';

    public function __construct($key, $salt)
    {
        $this->$key = $key;
        $this->$salt = $salt;
    }

    public function encrypt($data, $url_safe = false)
    {
        $data_arr = [];

        if (!is_array($data)) {
            $data_arr[self::DELIMITER . uniqid() . self::DELIMITER] = $data;
        } else {
            $data_arr = $data;
        }
        
        $data_arr = serialize($this->sign_data($data_arr));

        return openssl_encrypt($data_arr, self::ENCRYPTION_ALGORITHM, $this->key);
    }

    public function decrypt($string, $url_safe = false)
    {
        $data = openssl_decrypt($string, self::ENCRYPTION_ALGORITHM, $this->key);

        $data = unserialize($data);

        if($this->check_signature($data)){
            $data = $this->strip_signature($data);
            $keys = array_keys($data);
            if(count($data) == 1 && $this->surroundedBy($keys[0],self::DELIMITER)){
                return $data[$keys[0]];
            }
            return $data;
        }
        return false;
    }

    private function sign_data(array $data)
    {
        $salted_array = array_merge($data, ['salt' => $this->salt]);
        $signature = md5(serialize($salted_array));

        $signed_data = array_merge($data, ['signature' => $signature]);
        return $signed_data;
    }

    private function check_signature(array $data)
    {
        $data_signature = $data['signature'];
        unset($data['signature']);

        $salted_array = array_merge($data, ['salt' => $this->salt]);
        $temp_signature = md5(serialize($salted_array));

        if ($data_signature === $temp_signature) return true;

        return false;
    }

    private function strip_signature(array $data): array
    {
        unset($data['signature']);
        return $data;
    }

    // UTIL
    private function surroundedBy($string, $delimiter){
        return $this->startsWith($string,$delimiter) && $this->endsWith($string,$delimiter);
    }

    private function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return substr($haystack, 0, $length) === $needle;
    }

    private function endsWith( $haystack, $needle ) 
    {
        $length = strlen( $needle );
        if( !$length ) return true;
        return substr( $haystack, -$length ) === $needle;
    }
}
