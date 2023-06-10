# Base64 Encryption
The Base64Encryption class provides a simple two-way encryption based on MIME base64.

## Installation
```
composer require nawawi/base64-encryption
```

## Example
```php
<?php
use Nawawi\Base64Encryption;

// With default key
$encryption = Base64Encryption();
$encrypted = $encryption->encrypt('test123');
$decrypted = $encryption->decrypt($encrypted);

echo "\$encrypted = $encrypted\n";
echo "\$decrypted = $decrypted\n";

// Set key on initiation
$encryption = Base64Encryption('mysecretkey');
$encrypted = $encryption->encrypt('test123');
$decrypted = $encryption->decrypt($encrypted);

echo "\$encrypted = $encrypted\n";
echo "\$decrypted = $decrypted\n";

// Set key with set_key method
$encryption = Base64Encryption();
$encryption->set_key('mysecretkey');
$encrypted = $encryption->encrypt('test123');
$decrypted = $encryption->decrypt($encrypted);

echo "\$encrypted = $encrypted\n";
echo "\$decrypted = $decrypted\n";

// Set key on call
$encryption = Base64Encryption();
$encrypted = $encryption->encrypt('test123', 'mysecretkey');
$decrypted = $encryption->decrypt($encrypted, 'mysecretkey');

echo "\$encrypted = $encrypted\n";
echo "\$decrypted = $decrypted\n";
```

## Secret key
You can set the secret key using env variables or constants.

```
# env
BASE64_ENCRYPTION_KEY = "mysecretkey"
```

```php
// Constant
define('BASE64_ENCRYPTION_KEY', 'mysecretkey');
```
