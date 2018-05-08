<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Struct;

use Doctrine\Common\Collections\ArrayCollection;

class Projects
{
    /** @var ArrayCollection|Project[] */
    public $project;
}
