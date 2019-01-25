<?php
/**
 * @see https://gist.github.com/YarekTyshchenko/4092308fc5fa87ad1791
 */

$options = getopt("d:o:");
if (!isset($options['d'])) {
    echo "Input directory has to be specified with -d\n";
    exit(1);
}

if (!isset($options['o'])) {
    echo "Output file has to be specified with -o\n";
    exit(1);
}

$directoryIterator = new DirectoryIterator($options['d']);
$output = $options['o'];
$buffer = '';
foreach ($directoryIterator as $file) {
    if ($file->isDot()) {
        continue;
    }

    $report = simplexml_load_file($file->getPathname());
    $buffer .= $report->project->asXML();
}

$fh = fopen($output, 'w');
if (!$fh) {
    echo "Cannot open '$output' for writing\n";
    exit(2);
}
fwrite($fh, sprintf('<?xml version="1.0" encoding="UTF-8"?><coverage>%s</coverage>', $buffer));
fclose($fh);