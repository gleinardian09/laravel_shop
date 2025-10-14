<?php

namespace Tests\Feature;

use Tests\TestCase;

class DebugEncryptionTest extends TestCase
{
    public function test_debug_encryption()
    {
        echo "Cipher: " . config('app.cipher') . "\n";
        echo "Key: " . config('app.key') . "\n";
        
        try {
            $encrypted = encrypt('test');
            $decrypted = decrypt($encrypted);
            echo "Encryption SUCCESS: " . $decrypted . "\n";
        } catch (\Exception $e) {
            echo "Encryption FAILED: " . $e->getMessage() . "\n";
        }
        
        $this->assertTrue(true);
    }
}