<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Query;

use App\Application\Exception\NotFoundException;
use App\Application\Query\HouseholdQuery;
use Doctrine\DBAL\Connection;

final class DbalHouseholdQuery implements HouseholdQuery
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getHouseholdsOfPerson(string $personId): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select(['h.id', 'h.name', 'ho.is_owner'])
            ->from('household_occupants', 'ho')
            ->join('ho', 'households', 'h', 'ho.household_id = h.id')
            ->where('ho.person_id = :id')
            ->setParameter('id', $personId);

        $result = [];

        $petsQuery = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('pet')
            ->where('household_id IN (SELECT household_id FROM household_occupants WHERE person_id = :id)')
            ->setParameter('id', $personId);

        $pets = [];

        foreach ($this->connection->fetchAll($petsQuery->getSQL(), $petsQuery->getParameters()) as $pet) {
            $pets[$pet['household_id']][] = $pet;
        }

        foreach ($this->connection->fetchAll($query->getSQL(), $query->getParameters()) as $row) {
            $row['pets'] = $pets[$row['id']] ?? [];
            $result[] = $row;
        }

        return $result;
    }

    public function getHouseholdById(string $id): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select(['*'])
            ->from('households')
            ->where('id = :id')
            ->setParameter('id', $id);

        $household = $this->connection->fetchAssoc($query->getSQL(), $query->getParameters());

        if (!$household) {
            throw NotFoundException::householdNotFound($id);
        }

        $occupantsQuery = $this->connection->createQueryBuilder()
            ->select(['person_id', 'is_owner'])
            ->from('household_occupants')
            ->where('household_id = :id')
            ->setParameter('id', $id);

        $occupants = $this->connection->fetchAll($occupantsQuery->getSQL(), $occupantsQuery->getParameters());
        $household['occupants'] = $occupants;

        $petsQuery = $this->connection->createQueryBuilder()
            ->select(['id', 'name', 'owner_id'])
            ->from('pet')
            ->where('household_id = :id')
            ->setParameter('id', $id);

        $pets = $this->connection->fetchAll($petsQuery->getSQL(), $petsQuery->getParameters());
        $household['pets'] = $pets;

        return $household;
    }
}
