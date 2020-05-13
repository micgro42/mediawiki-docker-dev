<?php

namespace Addshore\Mwdd\Command\V0;

use Addshore\Mwdd\Files\InstallDir;
use Addshore\Mwdd\Shell\Docker;
use Addshore\Mwdd\Shell\Git;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Bash extends Command {

	protected static $defaultName = 'v0:setup';

	protected function configure() {
		$this->addArgument( 'dir' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
		$dir = $input->getArgument( 'dir' );
		$installDir = new InstallDir( $dir );
		$mwDir = $dir . '/mediawiki';

		// Clone the minimum needed code
		$installDir->ensurePresent();
		( new Git() )->clone( 'https://gerrit.wikimedia.org/r/mediawiki/core', $mwDir );
		( new Git() )->clone( 'https://gerrit.wikimedia.org/r/mediawiki/skins/Vector',
			$dir
			. '/mediawiki/skins/Vector' );

		// Run composer install
		( new Docker() )->runComposerInstall( $mwDir );

		// Create the basic local settings file...
		$lsFile = $mwDir . '/LocalSettings.php';
		$initialLocalSettings = <<<EOT
<?php
require_once __DIR__ . '/.docker/LocalSettings.php';
wfLoadSkin( 'Vector' );
EOT;
		file_put_contents( $lsFile, $initialLocalSettings );
	}

}