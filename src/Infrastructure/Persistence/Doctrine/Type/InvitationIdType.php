<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\InvitationId;

final class InvitationIdType extends AbstractUuidType
{
    const NAME = 'invitation_id';

    public function getName()
    {
        return static::NAME;
    }

    protected function getValueObjectClassName()
    {
        return InvitationId::class;
    }
}
