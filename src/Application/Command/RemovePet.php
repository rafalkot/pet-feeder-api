<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\PetId;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;

final class RemovePet extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public static function withId(string $id): self
    {
        return new self(['pet_id' => $id]);
    }

    public function petId(): PetId
    {
        return PetId::fromString($this->payload['pet_id']);
    }
}
