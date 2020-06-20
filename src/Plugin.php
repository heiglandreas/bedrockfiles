<?php

declare(strict_types=1);

namespace Org_Heigl\BedrockFiles;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use SplFileInfo;
use function getcwd;

class Plugin implements PluginInterface, EventSubscriberInterface
{
	/** @var IOInterface */
	private $io;

	/** @var Composer */
	private $composer;

	/**
	 * @return void
	 */
	public function activate(Composer $composer, IOInterface $io)
	{
		$this->composer = $composer;
		$this->io = $io;
	}

	/**
	 * @return void
	 */
	public function deactivate(Composer $composer, IOInterface $io)
	{
		$this->composer = $composer;
		$this->io = $io;
	}

	/**
	 * @return void
	 */
	public function uninstall(Composer $composer, IOInterface $io)
	{
		$this->composer = $composer;
		$this->io = $io;
	}

	public static function getSubscribedEvents()
	{
		return [
			PackageEvents::POST_PACKAGE_INSTALL => [
				['postPackageUpdate', 0],
			],
			PackageEvents::POST_PACKAGE_UPDATE => [
				['postPackageUpdate', 0],
			]
		];
	}

	public function postPackageUpdate(PackageEvent $event): void
	{
		$installer = new Installer(new SplFileInfo(getcwd()), $this->io);
		$installer->install();
	}
}
