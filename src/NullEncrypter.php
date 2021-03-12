<?php declare(strict_types=1);

namespace App\Library\Packages\Encryption;

use CustomerGauge\Encryption\Contracts\Encrypter;

final class NullEncrypter implements Encrypter
{
    public function encryptString(string $value): string
    {
        return $this->encrypt($value, false);
    }

    public function decryptString(string $value): string
    {
        return $this->decrypt($value, false);
    }

    public function encrypt($value, $serialize = true)
    {
        return base64_encode($value);
    }

    public function decrypt($payload, $unserialize = true)
    {
        return base64_decode($payload);
    }
}
