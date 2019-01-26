<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class InvalidArgumentException extends Exception
{
    public static function invalidRecurrenceRule(string $recurrenceRule, \Throwable $previous): self
    {
        return new self(
            sprintf(
                'Invalid recurrence rule string given: "%s", see prev exception for details',
                $recurrenceRule
            ),
            0,
            $previous
        );
    }
}
