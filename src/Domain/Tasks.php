<?php

declare(strict_types=1);

namespace App\Domain;

interface Tasks
{
    public function save(Task $person): void;

    public function delete(Task $person): void;

    public function findById(TaskId $id): ? Task;

    public function getById(TaskId $id): Task;
}
