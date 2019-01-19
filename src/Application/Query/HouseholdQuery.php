<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Exception\NotFoundException;

interface HouseholdQuery
{
    public function getHouseholdsOfPerson(string $personId): array;

    /**
     * @throws NotFoundException
     */
    public function getHouseholdById(string $id): array;
}
