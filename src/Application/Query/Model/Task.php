<?php

declare (strict_types=1);


namespace App\Application\Query\Model;


final class Task
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $recurrence;

    /**
     * @var string
     */
    public $hour;

    /**
     * @var string
     */
    public $timeZone;

    /**
     * @var SimplePet
     */
    public $pet;
}

