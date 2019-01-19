<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\UpdatePetProfile;
use App\Domain\Pets;

final class UpdatePetProfileHandler
{
    /**
     * @var Pets
     */
    private $pets;

    public function __construct(Pets $pets)
    {
        $this->pets = $pets;
    }

    public function __invoke(UpdatePetProfile $command): void
    {
        $pet = $this->pets->getById($command->petId());

        $pet->setBirthYear($command->birthYear());
        $pet->setGender($command->gender());

        $this->pets->save($pet);
    }
}
