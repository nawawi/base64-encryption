<?php
/**
 * Base64 Encryption.
 *
 * @author  Nawawi Jamili
 * @license MIT
 *
 * @see    https://github.com/nawawi/base64-encryption
 */

namespace Nawawi\Base64Encryption;

class Base64Encryption
{
    private $prefix = 'a0bc09';
    private $key;

    public function __construct($key = '')
    {
        $this->key = !empty($key) && \is_string($key) ? $key : $this->get_default_key();
    }

    public function encrypt($value, $key = '')
    {
        if (!\is_scalar($value) || '' === $value || $this->is_encrypted($value)) {
            return $value;
        }

        $mykey = !empty($key) && \is_string($key) ? $key : $this->key;
        $enc_pad = substr($mykey, 0, 12);
        $value = $enc_pad.$value;

        $pad = $this->obfuscate($mykey);
        if (false === $pad) {
            return $encrypted_value;
        }

        $value_length = \strlen($value);
        $encrypted = '';
        $x = 0;
        for ($i = 0; $i < $value_length; $i++, $x++) {
            if (!isset($pad[$x])) {
                $x = 0;
            }
            $encrypted .= \chr(\ord($value[$i]) ^ \ord($pad[$x]));
        }

        return $this->prefix.$this->encode($encrypted);
    }

    public function decrypt($encrypted_value, $key = '')
    {
        if (!\is_string($encrypted_value) || '' === $encrypted_value || !$this->is_encrypted($encrypted_value)) {
            return $encrypted_value;
        }

        $value = substr($encrypted_value, \strlen($this->prefix));
        $mykey = !empty($key) && \is_string($key) ? $key : $this->key;

        $pad = $this->obfuscate($mykey);
        if (false === $pad) {
            return $encrypted_value;
        }

        $decoded = $this->decode($value);
        $decoded_length = \strlen($decoded);
        $decrypted = '';
        $x = 0;
        for ($i = 0; $i < $decoded_length; $i++, $x++) {
            if (!isset($pad[$x])) {
                $x = 0;
            }
            $decrypted .= \chr(\ord($decoded[$i]) ^ \ord($pad[$x]));
        }

        $enc_pad = substr($mykey, 0, 12);
        $enc_len = \strlen($enc_pad);
        $env_pad = substr($decrypted, 0, $enc_len);

        if ($enc_pad !== $env_pad) {
            return $encrypted_value;
        }

        $decrypted = substr($decrypted, $enc_len);

        return $decrypted;
    }

    public function is_encrypted($value)
    {
        return preg_match('@^'.preg_quote($this->prefix, '@').'([a-zA-Z0-9\-_]+)$@', substr($value, 0, 128));
    }

    public function set_prefix($value)
    {
        $this->prefix = $value;

        return $this->prefix;
    }

    public function set_key($value)
    {
        $this->key = (string) $value;

        return $this->key;
    }

    private function get_env($key)
    {
        if (\function_exists('env')) {
            return env($key);
        }

        if (\function_exists('getenv')) {
            return getenv($key);
        }

        return false;
    }

    private function get_default_key()
    {
        if ($key = $this->get_env('BASE64_ENCRYPTION_KEY')) {
            if (\is_string($key)) {
                return (string) $key;
            }
        }

        if (\defined('BASE64_ENCRYPTION_KEY') && !empty(BASE64_ENCRYPTION_KEY) && \is_string(BASE64_ENCRYPTION_KEY)) {
            return BASE64_ENCRYPTION_KEY;
        }

        return 'd668d07a8bf33b219c9936efd493e6632z';
    }

    private function encode($value)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($value));
    }

    private function decode($value)
    {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $value));
    }

    private function obfuscate($value)
    {
        return base64_decode($value);
    }
}
