<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../includes/functions/login.php';

class UserLoginTest extends TestCase
{
    public function testLoginWithCorrectCredentials()
    {
        $result = validateLogin('admin@example.com', 'admin123'); // 🔁 replace with a real user
        $this->assertTrue($result['success']);
    }

    public function testLoginWithWrongPassword()
    {
        $result = validateLogin('admin@example.com', 'wrongpassword');
        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid credentials', $result['message']);
    }

    public function testLoginWithNonexistentUser()
    {
        $result = validateLogin('fakeuser@xyz.com', 'any');
        $this->assertFalse($result['success']);
        $this->assertEquals('User not found', $result['message']);
    }
}
