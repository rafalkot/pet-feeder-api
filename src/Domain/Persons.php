<?php

declare(strict_types=1);

namespace App\Domain;

interface Persons
{
    public function save(Person $person): void;

    public function delete(Person $person): void;

    public function findById(PersonId $id): ? Person;

    public function findByUsername(string $username): ? Person;

    public function findByEmail(string $email): ? Person;
}
