<?php

declare(strict_types=1);

namespace App\Domain;

interface Pets
{
    public function save(Pet $pet): void;

    public function delete(Pet $pet): void;

    public function findById(PetId $id): ? Pet;

    public function getById(PetId $id): Pet;

    /**
     * @return Pet[]
     */
    public function findAllByOwnerId(PersonId $id): array;
}
