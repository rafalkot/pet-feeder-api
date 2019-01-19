<?php

declare(strict_types=1);

namespace App\Ui\Rest\Controller;

use App\Application\Command\RegisterPet;
use App\Application\Command\RemovePet;
use App\Application\Command\UpdatePetProfile;
use App\Application\Query\PetQuery;
use App\Domain\Person;
use App\Ui\Rest\Form\PetForm;
use App\Ui\Rest\Form\PetProfileForm;
use FOS\RestBundle\View\View;
use Prooph\ServiceBus\CommandBus;
use Ramsey\Uuid\UuidInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

final class PetsController extends RestController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var PetQuery
     */
    private $query;

    public function __construct(CommandBus $commandBus, PetQuery $query)
    {
        $this->commandBus = $commandBus;
        $this->query = $query;
    }

    /**
     * @SWG\Tag(name="Pets")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(
     *              @SWG\Property(property="id", type="string", example="UUID"),
     *              @SWG\Property(property="name", type="string", example="Bobby"),
     *              @SWG\Property(property="type", type="string", example="cat", enum={"cat", "dog"}),
     *              @SWG\Property(property="gender", type="string", example="m", enum={"m", "f"}),
     *              @SWG\Property(property="birth_year", type="int", example=2018),
     *          )
     *     )
     * )
     */
    public function cgetAction(): View
    {
        /** @var Person $user */
        $user = $this->getUser();

        return $this->view(
            $this->query->getPets($user->id()->id())
        );
    }

    /**
     * @SWG\Tag(name="Pets")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="id", type="string", example="UUID"),
     *          @SWG\Property(property="name", type="string", example="Bobby"),
     *          @SWG\Property(property="type", type="string", example="cat", enum={"cat", "dog"}),
     *          @SWG\Property(property="gender", type="string", example="m", enum={"m", "f"}),
     *          @SWG\Property(property="birth_year", type="int", example=2018),
     *     )
     * )
     */
    public function getAction(UuidInterface $id): View
    {
        return $this->petView($id->toString());
    }

    /**
     * @SWG\Tag(name="Pets")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     type="object",
     *     required=true,
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="name", type="string", example="Bobby"),
     *          @SWG\Property(property="type", type="string", example="cat", enum={"cat", "dog"})
     *     )
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="id", type="string", example="UUID"),
     *          @SWG\Property(property="name", type="string", example="Bobby"),
     *          @SWG\Property(property="type", type="string", example="cat", enum={"cat", "dog"}),
     *          @SWG\Property(property="gender", type="string", example="m", enum={"m", "f"}),
     *          @SWG\Property(property="birth_year", type="int", example=2018),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     ref="#/definitions/ValidationErrorResponse"
     * )
     */
    public function postAction(Request $request): View
    {
        /** @var Person $user */
        $user = $this->getUser();
        $ownerId = $user->id();

        $form = $this->createForm(
            PetForm::class,
            [],
            [
                'owner_id' => $ownerId,
                'csrf_protection' => false,
            ]
        );

        /** @var RegisterPet $command */
        $command = $this->processForm($form, $request);
        $this->commandBus->dispatch($command);

        return $this->petView($command->petId()->id());
    }

    /**
     * @SWG\Tag(name="Pets")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     type="object",
     *     required=true,
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="name", type="string", example="Bobby"),
     *          @SWG\Property(property="type", type="string", example="cat", enum={"cat", "dog"}),
     *          @SWG\Property(property="gender", type="string", example="m", enum={"m", "f"}),
     *          @SWG\Property(property="birth_year", type="int", example=2018)
     *     )
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="id", type="string", example="UUID"),
     *          @SWG\Property(property="name", type="string", example="Bobby"),
     *          @SWG\Property(property="type", type="string", example="cat", enum={"cat", "dog"}),
     *          @SWG\Property(property="gender", type="string", example="m", enum={"m", "f"}),
     *          @SWG\Property(property="birth_year", type="int", example=2018),
     *     )
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     ref="#/definitions/ValidationErrorResponse"
     * )
     */
    public function putAction(UuidInterface $id, Request $request): View
    {
        $this->getPet($id->toString());

        $form = $this->createForm(
            PetProfileForm::class,
            [],
            [
                'pet_id' => $id->toString(),
                'csrf_protection' => false,
            ]
        );

        /** @var UpdatePetProfile $command */
        $command = $this->processForm($form, $request);
        $this->commandBus->dispatch($command);

        return $this->petView($id->toString());
    }

    /**
     * @SWG\Tag(name="Pets")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Success"
     * )
     */
    public function deleteAction(UuidInterface $id): View
    {
        $this->getPet($id->toString());

        $this->commandBus->dispatch(RemovePet::withId($id->toString()));

        return $this->view();
    }

    private function petView(string $id): View
    {
        return $this->view($this->getPet($id));
    }

    private function getPet(string $id)
    {
        /** @var Person $person */
        $person = $this->getUser();

        return $this->query->getPetById($person->id()->id(), $id);
    }
}
