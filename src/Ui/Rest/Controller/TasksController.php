<?php

declare(strict_types=1);

namespace App\Ui\Rest\Controller;

use App\Application\Command\ScheduleTask;
use App\Application\Query\TaskQuery;
use App\Domain\Person;
use App\Ui\Rest\Form\TaskForm;
use Prooph\ServiceBus\CommandBus;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

final class TasksController extends RestController
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
     *     path="/api/tasks"
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
     *              @SWG\Property(property="recurrence", type="string", example="FREQ=DAILY"),
     *              @SWG\Property(property="hours", type="string", example="08:00:00"),
     *              @SWG\Property(property="time_zone", type="string", example="Europe\Warsaw"),
     *              @SWG\Property(property="pet", type="object",
     *                  @SWG\Property(property="id", type="string", example="UUID"),
     *                  @SWG\Property(property="name", type="string", example="Bobby")
     *              )
     *          )
     *     )
     * )
     */
    public function cgetAction(Request $request)
    {
        /** @var Person $user */
        $user = $this->getUser();
        $petId = $request->query->get('pet_id', null);

        return $this->view(
            $this->query->getTasksByPetAndPersonId($petId, (string) $user->id())
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
     *          @SWG\Property(property="pet_id", type="string", example="UUID"),
     *          @SWG\Property(property="name", type="string", example="Walk"),
     *          @SWG\Property(property="recurrence", type="string", example="FREQ=DAILY"),
     *          @SWG\Property(property="hours", type="string", example="08:00:00"),
     *          @SWG\Property(property="time_zone", type="string", example="Europe\Warsaw")
     *     )
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="id", type="string", example="UUID"),
     *          @SWG\Property(property="name", type="string", example="Walk"),
     *          @SWG\Property(property="recurrence", type="string", example="FREQ=DAILY"),
     *          @SWG\Property(property="hours", type="string", example="08:00:00"),
     *          @SWG\Property(property="time_zone", type="string", example="Europe\Warsaw"),
     *          @SWG\Property(property="pet", type="object",
     *              @SWG\Property(property="id", type="string", example="UUID"),
     *              @SWG\Property(property="name", type="string", example="Bobby")
     *          )
     *     )
     * )
     */
    public function postAction(Request $request)
    {
        /** @var Person $user */
        $user = $this->getUser();
        $ownerId = $user->id();

        $form = $this->createForm(
            TaskForm::class,
            [],
            [
                'owner_id' => $ownerId,
                'csrf_protection' => false,
            ]
        );

        /** @var ScheduleTask $command */
        $command = $this->processForm($form, $request);
        $this->commandBus->dispatch($command);

        return $this->view(
            $this->query->getTaskById($user->id()->id(), $command->taskId()->id())
        );
    }
}
