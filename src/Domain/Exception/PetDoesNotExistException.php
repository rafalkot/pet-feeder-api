<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Domain\PetId;

final class PetDoesNotExistException extends Exception
{
    public static function withId(PetId $petId): self
    {
        return new self(sprintf('Pet with ID "%s" does not exist', (string) $petId));
    }
}
