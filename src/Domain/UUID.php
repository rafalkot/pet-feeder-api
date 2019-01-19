<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exception\InvalidArgumentException;

class UUID
{
    /**
     * @var string
     */
    protected $id;

    public function __construct(string $uuid)
    {
        $this->validate($uuid);

        $this->id = $uuid;
    }

    /**
     * @return static
     */
    public static function generate()
    {
        return new static(\Ramsey\Uuid\Uuid::uuid4()->toString());
    }

    /**
     * @return static
     */
    public static function fromString(string $uuid)
    {
        return new static($uuid);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function equals(self $id): bool
    {
        return $this->id === $id->id;
    }

    private function validate(string $uuid): void
    {
        if (!\Ramsey\Uuid\Uuid::isValid($uuid)) {
            throw new InvalidArgumentException(sprintf('Invalid UUID string given: "%s"', $uuid));
        }
    }
}
