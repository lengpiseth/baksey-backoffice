<?php
$startTime = microtime(1);
if (!is_file('composer.json')) {
    throw new \RuntimeException('This script must be started from the project root folder');
}

$rootDir = __DIR__ . '/..';

require_once __DIR__ . '/../app/bootstrap.php.cache';

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

$fs     = new Filesystem();
$output = new Symfony\Component\Console\Output\ConsoleOutput();

function execute_commands($commands, $output)
{
    foreach($commands as $command) {
        $output->writeln(sprintf('<info>Executing : </info> %s', $command));
        $p = new \Symfony\Component\Process\Process($command);
        $p->setTimeout(null);
        $p->run(function($type, $data) use ($output) {
            $output->write($data, false, OutputInterface::OUTPUT_RAW);
        });

        if (!$p->isSuccessful()) {
            return false;
        }

        $output->writeln("");
    }

    return true;
}

$bin = 'php';

if(defined('PHP_BINARY')) {
    $bin = PHP_BINARY;
}

$output->writeln("<info>Optimize autoload and clear cache</info>");

$success = execute_commands(array(
    'composer dumpautoload',
    'composer dumpautoload --optimize',
    '"'.$bin.'" ./app/console cache:clear --env=dev',
    '"'.$bin.'" ./app/console cache:clear --env=prod'
),$output);

if(!$success) {
    $output->writeln("<info>An error occurs when running command!</info>");

    exit(1);
}

$endTime = microtime(1);
$elapsedTime = (int) ($endTime - $startTime);

$output->writeln("<info>Done! in ~".$elapsedTime."seconds</info>");

exit(0);