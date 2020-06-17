<?php

declare(strict_types=1);

namespace Org_Heigl\BedrockFilesTest;

use DirectoryIterator;
use Org_Heigl\BedrockFiles\Installer;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use function mkdir;
use function rmdir;
use function unlink;

class InstallerTest extends TestCase
{
	public function setUp() : void
	{
		parent::setUp();

		mkdir(__DIR__ . '/_assettest', 0777);
	}

	public function tearDown() : void
	{
		parent::tearDown();
		$this->removeDirectoryRecursive(__DIR__ . '/_assettest');
	}

	private function removeDirectoryRecursive(string $directory): void
	{
		$iterator = new DirectoryIterator($directory);
		foreach ($iterator as $item) {
			if ($item->isDot()) {
				continue;
			}
			if ($item->isDir()) {
				$this->removeDirectoryRecursive($item->getPathname());
				continue;
			}
			unlink($item->getPathname());
		}
		rmdir($directory);
	}

	public function testInstall()
    {
		$installer = new Installer(new SplFileInfo(__DIR__ . '/_assettest'));
		$installer->install();

		TestCase::assertDirectoryExists(__DIR__ . '/_assettest/config');
		TestCase::assertFileExists(__DIR__ . '/_assettest/config/environments/development.php');
    }
}
