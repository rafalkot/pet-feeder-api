<?php

declare(strict_types=1);

namespace App\Ui\Rest\Controller;

use App\Application\Command\CompleteTask;
use App\Application\Command\IncompleteTask;
use App\Application\Query\TaskQuery;
use App\Domain\Person;
use Prooph\ServiceBus\CommandBus;
use Ramsey\Uuid\UuidInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

final class ScheduledtasksController extends RestController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var TaskQuery
     */
    private $query;

    public function __construct(CommandBus $commandBus, TaskQuery $query)
    {
        $this->commandBus = $commandBus;
        $this->query = $query;
    }

    /**
     * @SWG\Tag(name="Tasks")
     *
     * @SWG\Get(
     *     path="/api/scheduledtasks"
     * )
     *
     * @SWG\Parameter(
     *     in="query",
     *     name="pet_id",
     *     type="string"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(
     *              @SWG\Property(property="id", type="string", example="UUID"),
     *              @SWG\Property(property="name", type="string", example="Walk"),
     *              @SWG\Property(property="date", type="string", example="2018-01-01 08:00:00"),
     *              @SWG\Property(property="pet", type="object",
     *                  @SWG\Property(property="id", type="string", example="UUID"),
     *                  @SWG\Property(property="name", type="string", example="Bobby")
     *              ),
     *              @SWG\Property(property="completed", type="object", description="May be null",
     *                  @SWG\Property(property="completed_at", type="string", example="2018-01-01 08:00:00"),
     *                  @SWG\Property(property="completed_by", type="string", example="UUID")
     *              )
     *          )
     *     )
     * )
     */
    public function cgetAction()
    {
        /** @var Person $user */
        $user = $this->getUser();

        return $this->view(
            $this->query->getScheduledTasks((string) $user->id())
        );
    }

    /**
     * @SWG\Tag(name="Tasks")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     type="object",
     *     required=true,
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="completed", type="bool", example=true)
     *     )
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     ref="#/definitions/ValidationErrorResponse"
     * )
     */
    public function putAction(UuidInterface $id, Request $request)
    {
        /** @var Person $user */
        $user = $this->getUser();

        $personId = $user->id()->id();
        $this->query->getTaskById($personId, $id->toString());

        $isCompleted = $request->request->getBoolean('completed', false);

        $command = $isCompleted ? CompleteTask::withData($id->toString(), $personId) : IncompleteTask::ofId($id->toString());

        $this->commandBus->dispatch($command);

        return $this->view(null, 200);
    }
}
