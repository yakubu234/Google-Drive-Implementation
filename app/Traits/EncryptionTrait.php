<?php

declare(strict_types=1);

namespace App\Traits;

use Crypt;
use Illuminate\Support\Facades\Log;
use Throwable;

trait EncryptionTrait
{
    use ApiResponse;
    /**
     * Encrypt a message
     * the link to the below encryption and decryption  https://stackoverflow.com/questions/16600708/how-do-you-encrypt-and-decrypt-a-php-string
     * $key = base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES)); you can regenerate a key by uncommenting this line.
     * @param string $message - message to encrypt
     * @param string $key - encryption key
     * @return string
     * @throws RangeException
     */

    public function newencryption($message, $key): string
    {
        $key = base64_decode($key);

        try {

            if (mb_strlen($key, '8bit') !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
                return 'Key is not the correct size (must be 32 bytes).';
            }
            $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

            $cipher = base64_encode(
                $nonce .
                    sodium_crypto_secretbox(
                        $message,
                        $nonce,
                        $key
                    )
            );
            sodium_memzero($message);
            sodium_memzero($key);
            return base64_encode($cipher);
        } catch (Throwable $e) {
            report($e);
            Log::info($e->getMessage());
            return $e->getMessage();
            // return false;
        }
    }

    public function newdecryption($encrypted, $key): string
    {
        $key = base64_decode($key);

        $decoded = base64_decode(base64_decode($encrypted));

        try {
            $nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
            $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

            $plain = sodium_crypto_secretbox_open(
                $ciphertext,
                $nonce,
                $key
            );
            if (!is_string($plain)) {
                return 'Invalid MAC';
            }
            sodium_memzero($ciphertext);
            sodium_memzero($key);
            return $plain;
        } catch (Throwable $e) {
            report($e);
            Log::info($e->getMessage());
            return $e->getMessage();
            // return false;
        }
    }

    public function generateNewSodiumEncryptionKey()
    {
        #incase you will be changinx the OPENSSL_ENCRYPTION_KEY at env hit this method
        return $key = base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES));
    }
}
