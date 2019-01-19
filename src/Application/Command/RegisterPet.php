<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\PersonId;
use App\Domain\PetId;
use App\Domain\PetType;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;

final class RegisterPet extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public static function withData(string $petId, string $ownerId, string $type, string $name): self
    {
        return new self(
            [
                'pet_id' => $petId,
                'owner_id' => $ownerId,
                'type' => $type,
                'name' => $name,
            ]
        );
    }

    public function petId(): PetId
    {
        return PetId::fromString($this->payload['pet_id']);
    }

    public function ownerId(): PersonId
    {
        return PersonId::fromString($this->payload['owner_id']);
    }

    public function type(): PetType
    {
        return new PetType($this->payload['type']);
    }

    public function name(): string
    {
        return $this->payload['name'];
    }
}
