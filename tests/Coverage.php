<?php

declare(strict_types=1);

namespace App\Tests;

use DirectoryIterator;
use Ramsey\Uuid\Uuid;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Driver\Xdebug;
use SebastianBergmann\CodeCoverage\Filter;

final class Coverage
{
    /**
     * @var CodeCoverage
     */
    private static $coverage;
    /**
     * @var string
     */
    private static $id;

    public static function start(): void
    {
        $filter = new Filter();
        $filter->addDirectoryToWhitelist('/app/src');
        $driver = new Xdebug($filter);

        self::$coverage = new CodeCoverage($driver, $filter);
        self::$coverage->setProcessUncoveredFilesFromWhitelist(true);
        self::$id = Uuid::uuid4()->toString();
        self::$coverage->start(self::$id);
    }

    public static function stop(): void
    {
        self::$coverage->stop();

        $writer = new \SebastianBergmann\CodeCoverage\Report\PHP();
        $writer->process(
            self::$coverage,
            __DIR__.'/../var/coverage/api/'.self::$id.'.php'
        );
    }

    public static function merge(): CodeCoverage
    {
        $mergedCoverage = new CodeCoverage();

        $directoryIterator = new DirectoryIterator(__DIR__.'/../var/coverage/api/');
        foreach ($directoryIterator as $file) {
            if ($file->isDot()) {
                continue;
            }

            $tempCoverage = include $file->getPathname();
            $mergedCoverage->merge($tempCoverage);
            unset($tempCoverage);
        }

        return $mergedCoverage;
    }
}
