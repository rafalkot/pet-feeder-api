<?php

declare(strict_types=1);

namespace App\Application\Exception;

interface ProvidesHttpStatusCode
{
    public function getHttpStatusCode(): int;
}
