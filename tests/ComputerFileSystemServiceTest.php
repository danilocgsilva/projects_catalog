<?php

namespace App\Tests;

use App\Services\ComputerFileSystemService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use ReflectionClass;

class ComputerFileSystemServiceTest extends TestCase
{
    public function testExistsFalse(): void
    {
        $computerFileSystem = new ComputerFileSystemService($this->getMockedFileSystem(false));
        $this->assertFalse($computerFileSystem->exists('my_database_file.sql'));
    }

    public function testExistsTrue(): void
    {
        $computerFileSystem = new ComputerFileSystemService($this->getMockedFileSystem(true));
        $this->assertTrue($computerFileSystem->exists('my_database_file.sql'));
    }

    public function testGetFileSystemAddressPath()
    {
        $classFilePath = $this->getClassFilePath(ComputerFileSystemService::class);
        $filePathValue = $classFilePath . "/../../var/database_backups/my_database_file.sql";

        $computerFileSystem = new ComputerFileSystemService($this->getMockedFileSystem(false));

        $this->assertSame(
            $computerFileSystem
                ->getFileSystemAddressPath("my_database_file.sql"), 
            $filePathValue
        );
    }

    private function getClassFilePath(string $className): string
    {
        $reflection = new ReflectionClass($className);
        $pathData = pathinfo($reflection->getFileName());
        return $pathData['dirname'];
    }

    private function getMockedFileSystem(bool $return): Filesystem
    {
        $classFilePath = $this->getClassFilePath(ComputerFileSystemService::class);

        /** @var Filesystem & \PHPUnit\Framework\MockObject\MockObject $symfonyFileSystem */
        $symfonyFileSystem = $this->createMock(Filesystem::class);
        $symfonyFileSystem
            ->method('exists')
            ->with($classFilePath . "/../../var/database_backups/my_database_file.sql")
            ->willReturn(value: $return);
        return $symfonyFileSystem;
    }
}
