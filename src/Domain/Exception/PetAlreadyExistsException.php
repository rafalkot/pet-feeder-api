<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Domain\PetId;

final class PetAlreadyExistsException extends Exception
{
    public static function withId(PetId $petId): self
    {
        return new self(sprintf('Pet with ID "%s" already exists', (string) $petId));
    }

    public static function withName(string $name): self
    {
        return new self(sprintf('Pet with name "%s" already exists', $name));
    }
}
