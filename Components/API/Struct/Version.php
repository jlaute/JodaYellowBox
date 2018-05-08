<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Struct;

class Version
{
    /** @var string */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var \DateTime */
    public $date;
}
