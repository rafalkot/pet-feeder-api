<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Exception\NotFoundException;
use App\Application\Query\Model\Pet;

interface PetQuery
{
    /**
     * @param string $personId
     * @return Pet[]
     */
    public function getPets(string $personId): array;

    /**
     * @throws NotFoundException
     */
    public function getPetById(string $personId, string $id): Pet;
}
