<?php
/**
 * Base64 Encryption.
 *
 * @author  Nawawi Jamili
 * @license MIT
 *
 * @see    https://github.com/nawawi/base64-encryption
 */

namespace Nawawi\Utils;

/**
 * Class responsible for encrypting and decrypting data.
 *
 * @since 1.0.0
 */
class Base64Encryption
{
    /**
     * The prefix to use in the encrypted string.
     *
     * @var string
     */
    private $prefix = 'a0bc09';

    /**
     * The key to use for encryption.
     *
     * @var string
     */
    private $key;

    /**
     * Constructor.
     *
     * @param string $secret_key (Optional) The encryption key
     */
    public function __construct($secret_key = '')
    {
        $this->key = !empty($secret_key) && \is_scalar($secret_key) ? trim((string) $secret_key) : $this->get_default_key();
    }

    /**
     * Encrypts a value.
     *
     * @param string $value      the value to encrypt
     * @param string $secret_key (Optional) The encryption key
     *
     * @return string the encrypted value, or original value on failure
     */
    public function encrypt($value, $secret_key = '')
    {
        if (!\is_scalar($value) || '' === $value || $this->is_encrypted($value)) {
            return $value;
        }

        $secret_key = !empty($secret_key) && \is_scalar($secret_key) ? trim((string) $secret_key) : $this->key;
        if (empty($secret_key)) {
            return $value;
        }

        $enc_pad = substr($secret_key, 0, 12);
        $value = $enc_pad.$value;

        $obfuscate = $this->obfuscate($secret_key);
        if (false === $obfuscate) {
            return $value;
        }

        $value_length = \strlen($value);
        $encrypted = '';
        $x = 0;
        for ($i = 0; $i < $value_length; $i++, $x++) {
            if (!isset($obfuscate[$x])) {
                $x = 0;
            }
            $encrypted .= \chr(\ord($value[$i]) ^ \ord($obfuscate[$x]));
        }

        return $this->prefix.$this->encode($encrypted);
    }

    /**
     * Decrypts a value.
     *
     * @param string $value      the value to decrypt
     * @param string $secret_key (Optional) The encryption key
     *
     * @return string the decrypted value, or original value on failure
     */
    public function decrypt($encrypted_value, $secret_key = '')
    {
        if (!\is_string($encrypted_value) || '' === $encrypted_value || !$this->is_encrypted($encrypted_value)) {
            return $encrypted_value;
        }

        $value = substr($encrypted_value, \strlen($this->prefix));
        $secret_key = !empty($secret_key) && \is_scalar($secret_key) ? trim((string) $secret_key) : $this->key;
        if (empty($secret_key)) {
            return $value;
        }

        $obfuscate = $this->obfuscate($secret_key);
        if (false === $obfuscate) {
            return $encrypted_value;
        }

        $decoded = $this->decode($value);
        $decoded_length = \strlen($decoded);
        $decrypted = '';
        $x = 0;
        for ($i = 0; $i < $decoded_length; $i++, $x++) {
            if (!isset($obfuscate[$x])) {
                $x = 0;
            }
            $decrypted .= \chr(\ord($decoded[$i]) ^ \ord($obfuscate[$x]));
        }

        $enc_pad = substr($secret_key, 0, 12);
        $enc_len = \strlen($enc_pad);
        $env_pad = substr($decrypted, 0, $enc_len);

        if ($enc_pad !== $env_pad) {
            return $encrypted_value;
        }

        $decrypted = substr($decrypted, $enc_len);

        return $decrypted;
    }

    /**
     * Verify the encrypted string.
     *
     * @param string $value the encrypted value
     *
     * @return bool returns true on success or false on failure
     */
    public function is_encrypted($value)
    {
        return preg_match('@^'.preg_quote($this->prefix, '@').'([a-zA-Z0-9\-_]+)$@', substr($value, 0, 128));
    }

    /**
     * Set the encrypted string prefix.
     *
     * @param string $value the prefix name
     *
     * @return string the prefix set
     */
    public function set_prefix($value)
    {
        $this->prefix = $value;

        return $this->prefix;
    }

    /**
     * Set the encryption key.
     *
     * @param string $value the encryption key
     *
     * @return string the encryption key set
     */
    public function set_key($value)
    {
        $this->key = (string) $value;

        return $this->key;
    }

    /**
     * Get the encryption key from env variable.
     *
     * @param string $name the variable name
     *
     * @return string|false the encryption key, or false on failure
     */
    private function get_env($name)
    {
        if (\function_exists('env')) {
            return env($name);
        }

        if (\function_exists('getenv')) {
            return getenv($name);
        }

        return false;
    }

    /**
     * Get the default key to use for encryption.
     *
     * @return string|false the encryption key, or false on failure
     */
    private function get_default_key()
    {
        if ($key = $this->get_env('BASE64_ENCRYPTION_KEY')) {
            if (\is_scalar($key)) {
                return trim((string) $key);
            }
        }

        if (\defined('BASE64_ENCRYPTION_KEY') && !empty(BASE64_ENCRYPTION_KEY) && \is_scalar(BASE64_ENCRYPTION_KEY)) {
            return trim((string) BASE64_ENCRYPTION_KEY);
        }

        return false;
    }

    /**
     * Encodes data with MIME base64.
     *
     * @param string $value the data to encode
     *
     * @return string the encoded data, as a string
     */
    private function encode($value)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($value));
    }

    /**
     * Decodes data encoded with MIME base64.
     *
     * @param string $value the encoded data
     *
     * @return mixed returns the decoded data or false on failure
     */
    private function decode($value)
    {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $value));
    }

    /**
     * Obfuscate data with MIME base64.
     *
     * @param string $value the data to Obfuscate
     *
     * @return mixed Returns the decoded data or false on failure. The returned data may be binary.
     */
    private function obfuscate($value)
    {
        return base64_decode($value);
    }
}
