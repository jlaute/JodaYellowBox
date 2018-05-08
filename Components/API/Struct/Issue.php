<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Struct;

class Issue
{
    /** @var string */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $status;

    /** @var string */
    public $author;

    /** @var string */
    public $description;
}
