<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Domain\PersonId;

final class PersonDoesNotExistException extends Exception
{
    public static function withId(PersonId $personId): self
    {
        return new self(sprintf('Person with ID "%s" does not exist', (string) $personId));
    }

    public static function withUsername(string $username): self
    {
        return new self(sprintf('Person with username "%s" does not exist', $username));
    }

    public static function withEmail(string $email): self
    {
        return new self(sprintf('Person with email "%s" does not exist', $email));
    }
}
