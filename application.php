<?php

require __DIR__.'/vendor/autoload.php';

use DFKI\ScorecardBundle\Command\ImportMetricsCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new ImportMetricsCommand);
$application->run();