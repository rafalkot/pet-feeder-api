<?php

declare(strict_types=1);

namespace App\Ui\Rest\EventListener;

use App\Domain\Person;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

final class AuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        /* @var $user Person */

        $data['id'] = (string) $user->id();
        $data['name'] = $user->getUsername();
        $data['email'] = $user->email();

        $event->setData($data);
    }
}
