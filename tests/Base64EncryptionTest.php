<?php

namespace Nawawi\Utils\Base64Encryption\Test;

use Nawawi\Utils\Base64Encryption;
use PHPUnit\Framework\TestCase;

class Base64EncryptionTest extends TestCase
{
    public function testEncrypt()
    {
        $encryption = new Base64Encryption();
        $encryption->set_key(123456);
        $string = 'abc def ghi';
        $encrypted = $encryption->encrypt($string);
        $this->assertEquals($string, $encryption->decrypt($encrypted));
    }
}
