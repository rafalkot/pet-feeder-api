<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Exception\InvalidArgumentException;
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
        $rule = new self($startDate, $ruleString);

        return $rule;
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

    private function __construct(\DateTimeImmutable $startDate, string $ruleString)
    {
        $this->validateRuleString($ruleString);

        $this->startDate = $startDate;
        $this->timeZone = $startDate->getTimezone();
        $this->ruleString = $ruleString;
    }

    private function validateRuleString(string $ruleString): void
    {
        try {
            $rule = new Rule();
            $rule->loadFromString($ruleString);
        } catch (\Exception $ex) {
            throw InvalidArgumentException::invalidRecurrenceRule($ruleString, $ex);
        }
    }
}
