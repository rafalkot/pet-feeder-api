<?php

declare (strict_types=1);


namespace App\Application\Query\Model;


final class Pet
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
    public $type;

    /**
     * @var string|null
     */
    public $gender;

    /**
     * @var int|null
     */
    public $birthYear;
}
