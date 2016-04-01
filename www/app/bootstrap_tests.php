<?php
use Symfony\Component\Process\Process;

require_once __DIR__ . '/bootstrap.php.cache';

Phake::setClient(Phake::CLIENT_PHPUNIT);

$process = new Process('php app/console doctrine:database:drop --force --env=test');
$process->setTimeout(120);
$process->run();
print $process->getOutput();

$process = new Process('php app/console doctrine:database:create --env=test');
$process->setTimeout(120);
$process->run();
if (!$process->isSuccessful()) {
    throw new \RuntimeException($process->getErrorOutput());
}
print $process->getOutput();

$process = new Process('php app/console doctrine:schema:create --env=test');
$process->setTimeout(120);
$process->run();
print $process->getOutput();

$process = new Process('php app/console doctrine:schema:update --force --env=test');
$process->setTimeout(120);
$process->run();
print $process->getOutput();

$process = new Process('php app/console doctrine:fixtures:load --env=test --no-interaction');
$process->setTimeout(120);
$process->run();
print $process->getOutput();

$process = new Process('php app/console sfynx:classification:fixtures --env=test');
$process->setTimeout(120);
$process->run();
print $process->getOutput();

$process = new Process('php app/console init:acl');
$process->setTimeout(120);
$process->run();
print $process->getOutput();

//$process = new Process('php app/console lexik:monolog-browser:schema-create --env=test');
//$process->setTimeout(120);
//$process->run();
//print $process->getOutput();

$process = new Process('php app/console cache:clear --env=test');
$process->setTimeout(120);
$process->run();
if (!$process->isSuccessful()) {
    throw new \RuntimeException($process->getErrorOutput());
}
print $process->getOutput();
