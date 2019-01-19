<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\PersonId;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;

final class RegisterPerson extends Command implements PayloadConstructable
{
    use PayloadTrait;

    public static function withData(string $personId, string $username, string $email, string $password): self
    {
        return new self(
            [
                'person_id' => $personId,
                'username' => $username,
                'email' => $email,
                'password' => $password,
            ]
        );
    }

    public function personId(): PersonId
    {
        return PersonId::fromString($this->payload['person_id']);
    }

    public function username(): string
    {
        return $this->payload['username'];
    }

    public function email(): string
    {
        return $this->payload['email'];
    }

    public function password(): string
    {
        return $this->payload['password'];
    }
}
