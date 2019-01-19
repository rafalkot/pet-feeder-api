<?php

declare(strict_types=1);

namespace App\Ui\Rest\Controller;

use App\Application\Command\RegisterPerson;
use App\Domain\Persons;
use App\Ui\Rest\Form\RegistrationForm;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Prooph\ServiceBus\CommandBus;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;

final class PersonsController extends RestController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var JWTTokenManagerInterface
     */
    private $tokenManager;

    /**
     * @var Persons
     */
    private $persons;

    public function __construct(
        CommandBus $commandBus,
        JWTTokenManagerInterface $tokenManager,
        Persons $persons
    ) {
        $this->commandBus = $commandBus;
        $this->tokenManager = $tokenManager;
        $this->persons = $persons;
    }

    /**
     * @SWG\Tag(name="Auth")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     type="object",
     *     required=true,
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="email", type="string", example="john@example.com"),
     *          @SWG\Property(property="username", type="string", example="john.doe"),
     *          @SWG\Property(property="password", type="string", example="s3cr3t")
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Successful registration",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="person_id", type="string", example="UUID"),
     *          @SWG\Property(property="token", type="string", example="jisd8787jewdjkdsi")
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     ref="#/definitions/ValidationErrorResponse"
     * )
     */
    public function postAction(Request $request)
    {
        $form = $this->createForm(
            RegistrationForm::class,
            $request,
            [
                'csrf_protection' => false,
            ]
        );

        /** @var RegisterPerson $command */
        $command = $this->processForm($form, $request);
        $this->commandBus->dispatch($command);

        $person = $this->persons->findById($command->personId());

        return $this->view(
            [
                'person_id' => $person->id()->id(),
                'token' => $this->tokenManager->create($person),
            ]
        );
    }
}
