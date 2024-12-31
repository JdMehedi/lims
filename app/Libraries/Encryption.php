<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Auth;

class Encryption
{

    private static $defskey = "BAT4vA0q2z1B0c5F"; // EncryptionKey
    private static $chiperMethod = 'aes-256-cbc';

    public static function safe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return urlencode($data);
    }

    public static function safe_b64decode($string)
    {
        $data = urldecode($string);
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public static function encodeId($id, $module = null)
    {
        if (!$module) $module = time();
        if (Auth::user()) {
            return Encryption::encode($module . '_' . $id . '_' . Auth::user()->id);
        } else {
            return Encryption::encode($module . '_' . $id);
        }
    }

    public static function decodeId($id, $module = null)
    {
        $ids = explode('_', Encryption::decode($id));
        if (count($ids) == 3 && Auth::user()) {
            if (Auth::user()->id == $ids[2]) {
                if ($module) {
                    if (strcmp($module, $ids[0]) == 0) {
                        return $ids[1];
                    }
                } else {
                    return $ids[1];
                }
            }
        }
        if (count($ids) == 2) {
            if ($module) {
                if (strcmp($module, $ids[0]) == 0) {
                    return $ids[1];
                }
            } else {
                return $ids[1];
            }
        }
        die('Invalid Id! 401');
        //return null;
    }

    public static function encode($value)
    {


        if (in_array(SELF::$chiperMethod, openssl_get_cipher_methods())) {
            $ivlen = openssl_cipher_iv_length(SELF::$chiperMethod);
            $iv = openssl_random_pseudo_bytes($ivlen);
            $ciphertext_raw = openssl_encrypt(
                $value,
                SELF::$chiperMethod,
                SELF::$defskey,
                OPENSSL_RAW_DATA,
                $iv
            );

            $hmac = hash_hmac('sha256', $ciphertext_raw, SELF::$chiperMethod, $as_binary = true);
            $encodedText = Encryption::safe_b64encode($iv . $hmac . $ciphertext_raw);
        } else {
            $encodedText = Encryption::safe_b64encode($value);
        }
        return $encodedText;
    }

    //Return decrypted string
    public static function decode($encodedText)
    {

        $c = Encryption::safe_b64decode($encodedText);
        if (in_array(SELF::$chiperMethod, openssl_get_cipher_methods())) {
            $ivlen = openssl_cipher_iv_length(SELF::$chiperMethod);
            $iv = substr($c, 0, $ivlen);
            $sha2len = 32;
            $ciphertext_raw = substr($c, $ivlen + $sha2len);
            $plainText = openssl_decrypt(
                $ciphertext_raw,
                SELF::$chiperMethod,
                SELF::$defskey,
                OPENSSL_RAW_DATA,
                $iv
            );
        } else {
            $plainText = Encryption::safe_b64decode($encodedText);
        }
        return $plainText;
    }

    public static function dataEncode($value)
    {
        return Encryption::safe_b64encode($value);
    }

    public static function dataDecode($value)
    {
        return Encryption::safe_b64decode($value);
    }

    public static function processDataEncrypt($data)
    {
        if (!$data) {
            return false;
        }
        // get IV length
        $ivlen = openssl_cipher_iv_length(Encryption::$chiperMethod);
        // Generate an initialization vector
        $iv = openssl_random_pseudo_bytes($ivlen);
        // Generate encrypted data
        $encrypted_data = openssl_encrypt($data, Encryption::$chiperMethod, Encryption::$defskey, 0, $iv);

        // put iv into encrypted data to decrypt later
        return Encryption::safe_b64encode($encrypted_data . '::' . $iv);
    }

    public static function processDataDecrypt($data)
    {
        if (!$data) {
            return false;
        }

        $data_array = explode('::', Encryption::safe_b64decode($data), 2);
        // Validation for old data
        if (count($data_array) < 2) {
            return false;
        }
        $encrypted_data = $data_array[0];
        $iv = $data_array[1];
        // Generate decrypted data
        return openssl_decrypt($encrypted_data, Encryption::$chiperMethod, Encryption::$defskey, 0, $iv);
    }
}
