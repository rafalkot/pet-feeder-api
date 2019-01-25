<?php

declare (strict_types=1);

namespace App\Tests;

use Ramsey\Uuid\Uuid;
use SebastianBergmann\CodeCoverage\CodeCoverage;

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
        self::$coverage = new CodeCoverage();
        self::$coverage->filter()->addDirectoryToWhitelist('/app/src');
        self::$id = Uuid::uuid4()->toString();
        self::$coverage->start(self::$id);
    }

    public static function stop(): void
    {
        self::$coverage->stop();

        $writer = new \SebastianBergmann\CodeCoverage\Report\Clover;
        $writer->process(
            self::$coverage,
            __DIR__.'/../var/coverage/api/'.self::$id.'.xml'
        );
    }
}
