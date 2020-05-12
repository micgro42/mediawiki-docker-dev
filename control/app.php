#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

if ( $_SERVER['PHP_SELF'] !== 'control/app.php' ) {
    echo "You are running the control app from the wrong context.\n";
    echo "Please run the app from the root mediawiki-docker-dev directory.\n";
    die();
}

use Symfony\Component\Console\Application;

$application = new Application('mwdd');

$application->add(new \Addshore\Mwdd\Command\V0\Suspend());
$application->add(new \Addshore\Mwdd\Command\V0\Resume());
$application->add(new \Addshore\Mwdd\Command\V0\Bash());
$application->add(new \Addshore\Mwdd\Command\V0\MySql());
$application->add(new \Addshore\Mwdd\Command\V0\PHPUnit());
$application->add(new \Addshore\Mwdd\Command\V0\PHPUnitFile());
$application->add(new \Addshore\Mwdd\Command\V0\Script());
$application->add(new \Addshore\Mwdd\Command\V0\LogsTail());
$application->add(new \Addshore\Mwdd\Command\V0\HostsAdd());

$application->run();