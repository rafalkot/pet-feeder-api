<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Query;

use App\Application\Exception\NotFoundException;
use App\Application\Query\PetQuery;
use Doctrine\DBAL\Connection;

final class DbalPetQuery implements PetQuery
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getPets(string $personId): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->where('owner_id = :id')
            ->from('pet')
            ->orderBy('name', 'ASC')
            ->setParameter('id', $personId);

        return array_map([$this, 'normalize'], $this->connection->fetchAll($query->getSQL(), $query->getParameters()));
    }

    public function getPetById(string $personId, string $id): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select(['*'])
            ->from('pet')
            ->where('id = :id AND owner_id = :personId')
            ->setParameter('id', $id)
            ->setParameter('personId', $personId);

        $pet = $this->connection->fetchAssoc($query->getSQL(), $query->getParameters());

        if (!$pet) {
            throw NotFoundException::petNotFound($id);
        }

        return $this->normalize($pet);
    }

    private function normalize(array $data): array
    {
        $data['birth_year'] = null === $data['birth_year'] ? null : (int) ($data['birth_year']);

        return $data;
    }
}
