<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Query;

use App\Application\Exception\NotFoundException;
use App\Application\Query\Model\SimplePet;
use App\Application\Query\Model\Task;
use App\Application\Query\TaskQuery;
use Doctrine\DBAL\Connection;

final class DbalTaskQuery implements TaskQuery
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getTasksByPetAndPersonId(?string $petId, ?string $personId): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select(['t.*', 'p.name as pet_name', 'p.type as pet_type'])
            ->from('task', 't')
            ->join('t', 'pet', 'p', 'p.id = t.pet_id');

        if ($petId) {
            $query->where('t.pet_id = :petId')->setParameter('petId', $petId);
        }

        if ($personId) {
            $query->where('p.owner_id = :ownerId')->setParameter('ownerId', $personId);
        }

        $tasks = $this->connection->fetchAll($query->getSQL(), $query->getParameters());

        return array_map([$this, 'normalize'], $tasks);
    }

    public function getTaskById(string $personId, string $id): Task
    {
        $query = $this->connection->createQueryBuilder()
            ->select(['t.*', 'p.name as pet_name', 'p.type as pet_type'])
            ->from('task', 't')
            ->join('t', 'pet', 'p', 't.pet_id = p.id')
            ->where('t.id = :id AND p.owner_id = :personId')
            ->setParameter('id', $id)
            ->setParameter('personId', $personId);

        $task = $this->connection->fetchAssoc($query->getSQL(), $query->getParameters());

        if (!$task) {
            throw NotFoundException::taskNotFound($id);
        }

        return $this->normalize($task);
    }

    public function getScheduledTasks(string $personId): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select(['t.*', 'p.name as pet_name'])
            ->from('task', 't')
            ->join('t', 'pet', 'p', 'p.id = t.pet_id')
            ->where('p.owner_id = :ownerId')->setParameter('ownerId', $personId);

        $tasks = $this->connection->fetchAll($query->getSQL(), $query->getParameters());

        $scheduledTasks = [];

        foreach ($tasks as $task) {
            $rule = json_decode($task['recurrence_rule'], true);

            $timezone = $rule['time_zone'];
            $startDate = \DateTime::createFromFormat(
                'Y-m-d H:i:s',
                $rule['start_date'],
                new \DateTimeZone($rule['time_zone'])
            );
            $endDate = (new \DateTime('now', new \DateTimeZone($timezone)))->setTime(23, 59, 59);
            $rule = new \Recurr\Rule($rule['rule'], $startDate, $endDate, $timezone);

            $transformer = new \Recurr\Transformer\ArrayTransformer();
            $constraint = new \Recurr\Transformer\Constraint\BetweenConstraint(
                (new \DateTime('now', new \DateTimeZone($timezone)))->setTime(0, 0, 0),
                (new \DateTime('now', new \DateTimeZone($timezone)))->setTime(23, 59, 59)
            );

            foreach ($transformer->transform($rule, $constraint, false) as $recurrence) {
                $completion = $this->findCompletion($task['id'], $recurrence->getStart()->format('Y-m-d'));

                $scheduledTasks[] = [
                    'id' => $task['id'],
                    'name' => $task['name'],
                    'pet' => [
                        'id' => $task['pet_id'],
                        'name' => $task['pet_name'],
                    ],
                    'date' => $recurrence->getStart()->format('Y-m-d H:i:s'),
                    'completed' => $completion,
                ];
            }
        }

        return $scheduledTasks;
    }

    private function normalize(array $data): Task
    {
        $rule = json_decode($data['recurrence_rule'], true);

        $task = new Task();
        $task->id = $data['id'];
        $task->name = $data['name'];
        $task->recurrence = $rule['rule'];
        $task->timeZone = $rule['time_zone'];
        $task->hour = \DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $rule['start_date'],
            new \DateTimeZone($rule['time_zone'])
        )->format('H:i:s');

        $pet = new SimplePet();
        $pet->id = $data['pet_id'];
        $pet->name = $data['pet_name'];
        $pet->type = $data['pet_type'];

        $task->pet = $pet;

        return $task;
    }

    /**
     * @return array|bool
     */
    public function findCompletion(string $taskId, string $date)
    {
        $query = $this->connection->createQueryBuilder()
            ->select(['t.*'])
            ->from('task_completion', 't')
            ->where('t.task_id = :taskId AND DATE(t.completed_at) = :date')
            ->setParameter('taskId', $taskId)
            ->setParameter('date', $date);

        $completion = $this->connection->fetchAssoc($query->getSQL(), $query->getParameters());

        if (\is_array($completion)) {
            unset($completion['id'], $completion['task_id']);
        }

        return $completion;
    }
}
