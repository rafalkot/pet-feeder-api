<?php

declare(strict_types=1);

namespace App\Domain;

interface Households
{
    public function save(Household $household): void;

    public function delete(Household $household): void;

    public function findById(HouseholdId $id): ? Household;
}
