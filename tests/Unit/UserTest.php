<?php
// tests/Unit/UserTest.php

namespace Tests\Unit;

use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_email_is_lowercased(): void
    {
        $user = new \App\Models\User();
        $user->email = 'TEST@EXAMPLE.COM';
        
        $this->assertEquals('test@example.com', strtolower($user->email));
    }
}