<?php

namespace Spec\App\Domain;

use App\Domain\RecurrenceRule;
use PhpSpec\ObjectBehavior;

class RecurrenceRuleSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $startDate = new \DateTimeImmutable();

        $this->beConstructedThrough('create', [
            $startDate,
            'FREQ=DAILY',
        ]);

        $this->shouldHaveType(RecurrenceRule::class);
        $this->startDate()->shouldBe($startDate);
        $this->timeZone()->shouldBeLike($startDate->getTimezone());
        $this->ruleString()->shouldBe('FREQ=DAILY');
    }

    public function it_throws_exception_on_invalid_rrule_string()
    {
        $this->beConstructedThrough('create', [
            new \DateTime(),
            'INVALID_RULE',
        ]);

        $this->shouldThrow()->duringInstantiation();
    }
}
