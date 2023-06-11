# Base64 Encryption
The Base64Encryption class provides a simple two-way encryption based on MIME base64.

## Installation
```
composer require nawawi/base64-encryption
```

## Example
```php
<?php
if ( file_exists(__DIR__.'/vendor/autoload.php') ) {
    require_once __DIR__.'/vendor/autoload.php';
}

use Nawawi\Utils\Base64Encryption;

// With default key
$encryption = new Base64Encryption();
$encrypted = $encryption->encrypt('test123');
$decrypted = $encryption->decrypt($encrypted);

echo "\$encrypted = $encrypted\n";
echo "\$decrypted = $decrypted\n";

// Set key on initiation
$encryption = new Base64Encryption('encryption_key');
$encrypted = $encryption->encrypt('test123');
$decrypted = $encryption->decrypt($encrypted);

echo "\$encrypted = $encrypted\n";
echo "\$decrypted = $decrypted\n";

// Set key with set_key method
$encryption = new Base64Encryption();
$encryption->set_key('encryption_key');
$encrypted = $encryption->encrypt('test123');
$decrypted = $encryption->decrypt($encrypted);

echo "\$encrypted = $encrypted\n";
echo "\$decrypted = $decrypted\n";

// Set key on call
$encryption = new Base64Encryption();
$encrypted = $encryption->encrypt('test123', 'encryption_key');
$decrypted = $encryption->decrypt($encrypted, 'encryption_key');

echo "\$encrypted = $encrypted\n";
echo "\$decrypted = $decrypted\n";
```

## Encryption key
You may set the encryption key using env variables or constants.

```
# env
BASE64_ENCRYPTION_KEY = "encryption_key"
```

```php
// Constant
define('BASE64_ENCRYPTION_KEY', 'encryption_key');
```
