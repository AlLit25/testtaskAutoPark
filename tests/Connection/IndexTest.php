<?php
use PHPUnit\Framework\TestCase;

use function PHPSTORM_META\expectedArguments;

class IndexTest extends TestCase
{
    private $connection;

    protected function setUp(): void
    {
        $this->connection = new connection();
        $this->connection->setDbName("autopark");
    }

    protected function tearDown(): void
    {
        
    }

    public function testDbName(){
        $this->assertEquals("autopark", $this->connection->getDbName());
    }
}

?>