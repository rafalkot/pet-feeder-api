<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Exception\NotFoundException;

interface PetQuery
{
    public function getPets(string $personId): array;

    /**
     * @throws NotFoundException
     */
    public function getPetById(string $personId, string $id): array;
}
