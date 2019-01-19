<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Exception\NotFoundException;

interface TaskQuery
{
    public function getTasksByPetAndPersonId(?string $petId, ?string $personId): array;

    /**
     * @throws NotFoundException
     */
    public function getTaskById(string $personId, string $id): array;

    public function getScheduledTasks(string $personId): array;
}
