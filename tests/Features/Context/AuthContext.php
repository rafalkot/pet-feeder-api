<?php

declare(strict_types=1);

namespace App\Tests\Features\Context;

use App\Domain\Persons;
use Behat\Behat\Context\Context;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ubirak\RestApiBehatExtension\Rest\RestApiBrowser;

final class AuthContext implements Context
{
    /**
     * @var RestApiBrowser
     */
    private $restApiBrowser;

    /**
     * @var Persons
     */
    private $persons;

    /**
     * @var JWTTokenManagerInterface
     */
    private $tokenManager;

    /**
     * AuthContext constructor.
     */
    public function __construct(
        RestApiBrowser $restApiBrowser,
        Persons $persons,
        JWTTokenManagerInterface $tokenManager
    ) {
        $this->restApiBrowser = $restApiBrowser;
        $this->persons = $persons;
        $this->tokenManager = $tokenManager;
    }

    /**
     * @Given I am authenticated as :username
     */
    public function iAmAuthenticatedAs(string $username)
    {
        $person = $this->persons->findByUsername($username);
        $token = $this->tokenManager->create($person);

        $this->restApiBrowser->addRequestHeader('Authorization', 'Bearer '.$token);
    }
}
