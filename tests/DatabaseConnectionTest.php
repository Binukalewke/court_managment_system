<?php
use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    public function testConnectionIsPDO()
    {
        require __DIR__ . '/../includes/dbh.inc.php';
        $this->assertInstanceOf(PDO::class, $conn);
    }
}
