<?php

use App\Tests\Coverage;
use SebastianBergmann\CodeCoverage\Report\Clover;

$options = getopt("o:");
if (!isset($options['o'])) {
    echo "Output file has to be specified with -o\n";
    exit(1);
}

require __DIR__.'/../vendor/autoload.php';

$coverage = Coverage::merge();

$html = new SebastianBergmann\CodeCoverage\Report\Html\Facade();
$html->process($coverage, 'var/coverage/html');

$writer = new Clover();
$writer->process($coverage, $options['o']);