<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Gender;
use App\Domain\PetId;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;

final class UpdatePetProfile extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public static function withData(string $petId, ?string $gender, ?int $birthYear): self
    {
        return new self(
            [
                'pet_id' => $petId,
                'gender' => $gender,
                'birth_year' => $birthYear,
            ]
        );
    }

    public function petId(): PetId
    {
        return PetId::fromString($this->payload['pet_id']);
    }

    public function gender(): ?Gender
    {
        return $this->payload['gender'] ? new Gender($this->payload['gender']) : null;
    }

    public function birthYear(): ?int
    {
        return $this->payload['birth_year'];
    }
}
