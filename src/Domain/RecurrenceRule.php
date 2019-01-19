<?php

declare(strict_types=1);

namespace App\Domain;

use Recurr\Rule;

final class RecurrenceRule
{
    /**
     * @var \DateTimeImmutable
     */
    private $startDate;

    /**
     * @var \DateTimeZone
     */
    private $timeZone;

    /**
     * @var string
     */
    private $ruleString;

    public static function create(\DateTimeImmutable $startDate, string $ruleString)
    {
        try {
            new Rule($ruleString, $startDate);

            $rule = new self();
            $rule->startDate = $startDate;
            $rule->timeZone = $startDate->getTimezone();
            $rule->ruleString = $ruleString;

            return $rule;
        } catch (\Exception $ex) {
            throw new \InvalidArgumentException('Invalid recurrence rule: '.$ex->getMessage(), 0, $ex);
        }
    }

    public function startDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function timeZone(): \DateTimeZone
    {
        return $this->timeZone;
    }

    public function ruleString(): string
    {
        return $this->ruleString;
    }
}
