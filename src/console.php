<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;

$console = new Application();
$console->getDefinition()->addOption(
    new InputOption(
        '--env',
        '-e',
        InputOption::VALUE_REQUIRED,
        'The Environment name.',
        'dev'
    )
);

$console->add(new \Mindweb\Analyzer\AnalyzerCommand($container));

return $console;