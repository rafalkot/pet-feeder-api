<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Application\Exception\NotFoundException;
use App\Application\Query\Model\Task;

interface TaskQuery
{
    /**
     * @param null|string $petId
     * @param null|string $personId
     * @return Task[]
     */
    public function getTasksByPetAndPersonId(?string $petId, ?string $personId): array;

    /**
     * @throws NotFoundException
     */
    public function getTaskById(string $personId, string $id): Task;

    public function getScheduledTasks(string $personId): array;
}
