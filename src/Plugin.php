<?php

declare(strict_types=1);

namespace Org_Heigl\BedrockFiles;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use SplFileInfo;
use function dirname;

class Plugin implements PluginInterface
{
	/**
	 * @return void
	 */
	public function activate(Composer $composer, IOInterface $io)
	{
		$io->write(dirname(dirname(dirname(__DIR__))));
		$installer = new Installer(new SplFileInfo(dirname(dirname(dirname(__DIR__)))));
		$installer->install();
	}

	/**
	 * @return void
	 */
	public function deactivate(Composer $composer, IOInterface $io)
	{

	}

	/**
	 * @return void
	 */
	public function uninstall(Composer $composer, IOInterface $io)
	{

	}
}
