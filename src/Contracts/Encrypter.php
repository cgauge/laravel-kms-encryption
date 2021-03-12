<?php declare(strict_types=1);

namespace CustomerGauge\Encryption\Contracts;

use Illuminate\Contracts\Encryption\Encrypter as IlluminateEncrypter;

interface Encrypter extends IlluminateEncrypter
{
    public function encryptString(string $value): string;

    public function decryptString(string $payload): string;
}
