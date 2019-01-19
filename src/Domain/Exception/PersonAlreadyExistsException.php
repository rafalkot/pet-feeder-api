<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Domain\PersonId;

final class PersonAlreadyExistsException extends Exception
{
    public static function withId(PersonId $personId): self
    {
        return new self(sprintf('Person with ID "%s" already exists', (string) $personId));
    }

    public static function withUsername(string $username): self
    {
        return new self(sprintf('Person with username "%s" already exists', $username));
    }

    public static function withEmail(string $email): self
    {
        return new self(sprintf('Person with email "%s" already exists', $email));
    }
}
