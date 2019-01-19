<?php

declare(strict_types=1);

namespace App\Application\Exception;

class NotFoundException extends QueryException implements ProvidesHttpStatusCode
{
    /**
     * @var string
     */
    private $id;

    public static function householdNotFound(string $id)
    {
        $exception = new self(sprintf('Household with ID "%s" does not exist', $id));
        $exception->id = $id;

        return $exception;
    }

    public static function petNotFound(string $id)
    {
        $exception = new self(sprintf('Pet with ID "%s" does not exist', $id));
        $exception->id = $id;

        return $exception;
    }

    public static function taskNotFound(string $id)
    {
        $exception = new self(sprintf('Task with ID "%s" does not exist', $id));
        $exception->id = $id;

        return $exception;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getHttpStatusCode(): int
    {
        return 404;
    }
}
