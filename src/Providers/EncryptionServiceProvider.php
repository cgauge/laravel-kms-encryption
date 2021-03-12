<?php declare(strict_types=1);

namespace CustomerGauge\Encryption\Providers;

use Aws\Kms\KmsClient;
use CustomerGauge\Encryption\Contracts\Encrypter;
use CustomerGauge\Encryption\KmsEncrypter;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider;

final class EncryptionServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Encrypter::class, function () {
            /** @var Repository $repository */
            $repository = $this->app->make(Repository::class);

            $key = $repository->get('encryption.key');

            $context = $repository->get('encryption.context');

            $client = $this->app->make(KmsClient::class);

            return new KmsEncrypter($client, $key, $context);
        });
    }
}
