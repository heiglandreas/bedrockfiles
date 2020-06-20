<?php

declare(strict_types=1);

namespace Org_Heigl\BedrockFiles;

use Composer\IO\IOInterface;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use function copy;
use function dirname;
use function file_exists;
use function is_dir;
use function mkdir;
use const DIRECTORY_SEPARATOR;

class Installer
{
	private $targetPath;

	/** @var RecursiveIteratorIterator  */
	private $sourcePath;

	private $io;

	public function __construct(SplFileInfo $targetPath, IOInterface $io)
	{
		$this->sourcePath = new RecursiveDirectoryIterator(
			dirname(__DIR__) . '/share',
			FilesystemIterator::SKIP_DOTS ^ FilesystemIterator::KEY_AS_FILENAME ^ FilesystemIterator::CURRENT_AS_FILEINFO
		);
		$this->targetPath = $targetPath;
		$this->io = $io;
	}

	public function install(): void
	{
		$iterator = new RecursiveIteratorIterator($this->sourcePath);
		$basePathLength = strlen($this->sourcePath->getPath()) + 1;
		/** @var SplFileInfo $item */
		foreach ($iterator as $item) {
			$relativePath = substr($item->getPathname(), $basePathLength);
			$newFileName = $this->targetPath->getPathname() . DIRECTORY_SEPARATOR . $relativePath;
			if (file_exists($newFileName)) {
				continue;
			}
			if (! file_exists(dirname($newFileName))) {
				$this->mkdirr(dirname($newFileName));
			}
			$this->io->write(sprintf('Creating %s', $newFileName));
			copy($item->getPathname(), $newFileName);
		}
	}

	private function mkdirr(string $folder): void
	{
		if (! file_exists(dirname($folder))) {
			$this->mkdirr(dirname($folder));
		}
		if (is_dir($folder)) {
			return;
		}
		mkdir($folder, 0777);
	}
}
