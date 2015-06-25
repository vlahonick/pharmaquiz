<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('date.timezone', 'Asia/Jakarta');
// if you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

set_time_limit(0);

require_once '../app/bootstrap.php.cache';
require_once '../app/AppKernel.php';

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Debug\Debug;

$args[] = array(
    0 => "../app/console",
    1 => "assets:install",
    2 => "../web",
);
$args[] = array(
    0 => "../app/console",
    1 => "assetic:dump",
    2 => "--env=prod",
    3 => "--no-debug",
);
$args[] = array(
    0 => "../app/console",
    1 => "cache:clear",
    2 => "--env=prod",
    3 => "--no-debug",
);

header('Content-Type:text/plain');

foreach ($args as $arg) {
    $input = new ArgvInput($arg);
    $output = new StreamOutput(fopen('php://output', 'w'));
    $env = $input->getParameterOption(array('--env', '-e'), getenv('SYMFONY_ENV') ?: 'dev');
    $debug = getenv('SYMFONY_DEBUG') !== '0' && !$input->hasParameterOption(array('--no-debug', '')) && $env !== 'prod';

    if ($debug) {
        Debug::enable();
    }

    $kernel = new AppKernel($env, $debug);
    $application = new Application($kernel);
    $application->setCatchExceptions(FALSE);
    $application->setAutoExit(FALSE);
    $code = $application->run($input, $output);
}
