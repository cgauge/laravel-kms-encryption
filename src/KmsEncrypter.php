<?php declare(strict_types=1);

namespace CustomerGauge\Encryption;

use Aws\Exception\AwsException;
use Aws\Kms\KmsClient;
use CustomerGauge\Encryption\Contracts\Encrypter;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;

final class KmsEncrypter implements Encrypter
{
    private KmsClient $client;

    private string $key;

    private array $context;

    public function __construct(KmsClient $client, string $key, array $context)
    {
        $this->client = $client;
        $this->key = $key;
        $this->context = $context;
    }

    public function encrypt($value, $serialize = true)
    {
        try {
            return base64_encode($this->client->encrypt([
                'KeyId' => $this->key,
                'Plaintext' => $serialize ? serialize($value) : $value,
                'EncryptionContext' => $this->context,
            ])->get('CiphertextBlob'));
        } catch (AwsException $e) {
            throw new EncryptException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function decrypt($payload, $unserialize = true)
    {
        try {
            $result = $this->client->decrypt([
                'CiphertextBlob' => base64_decode($payload),
                'EncryptionContext' => $this->context,
            ]);

            $decrypted = $result['Plaintext'];

            return $unserialize ? unserialize($decrypted) : $decrypted;
        } catch (AwsException $e) {
            throw new DecryptException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function encryptString(string $value): string
    {
        return $this->encrypt($value, false);
    }

    public function decryptString(string $value): string
    {
        return $this->decrypt($value, false);
    }
}
