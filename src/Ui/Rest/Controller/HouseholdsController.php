<?php

declare(strict_types=1);

namespace App\Ui\Rest\Controller;

use App\Application\Command\CreateHousehold;
use App\Application\Command\InvitePersonToHousehold;
use App\Application\Query\HouseholdQuery;
use App\Domain\HouseholdId;
use App\Domain\Person;
use App\Ui\Rest\Form\HouseholdForm;
use App\Ui\Rest\Form\InvitationForm;
use Prooph\ServiceBus\CommandBus;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\Request;

final class HouseholdsController extends RestController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var HouseholdQuery
     */
    private $query;

    public function __construct(CommandBus $commandBus, HouseholdQuery $query)
    {
        $this->commandBus = $commandBus;
        $this->query = $query;
    }

    public function cgetAction()
    {
        /** @var Person $user */
        $user = $this->getUser();

        return $this->view(
            $this->query->getHouseholdsOfPerson((string) $user->id())
        );
    }

    public function getAction($id)
    {
        $household = $this->query->getHouseholdById($id);

        return $this->view($household);
    }

    public function postAction(Request $request)
    {
        /** @var Person $user */
        $user = $this->getUser();
        $id = (string) HouseholdId::generate();
        $ownerId = (string) $user->id();

        $form = $this->createForm(
            HouseholdForm::class,
            [],
            [
                'household_id' => $id,
                'owner_id' => $ownerId,
                'csrf_protection' => false,
            ]
        );

        /** @var CreateHousehold $command */
        $command = $this->processForm($form, $request);
        $this->commandBus->dispatch($command);

        return $this->view($this->query->getHouseholdById($id));
    }

    public function postInvitationAction(UuidInterface $householdId, Request $request)
    {
        $form = $this->createForm(
            InvitationForm::class,
            [],
            [
                'household_id' => $householdId->toString(),
                'csrf_protection' => false,
            ]
        );

        /** @var InvitePersonToHousehold $command */
        $command = $this->processForm($form, $request);
        $this->commandBus->dispatch($command);

        return $this->view(null, 201);
    }
}
