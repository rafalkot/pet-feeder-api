<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\RemovePet;
use App\Domain\Pets;

final class RemovePetHandler
{
    /**
     * @var Pets
     */
    private $pets;

    public function __construct(Pets $pets)
    {
        $this->pets = $pets;
    }

    public function __invoke(RemovePet $command): void
    {
        $pet = $this->pets->getById($command->petId());

        $this->pets->delete($pet);
    }
}
