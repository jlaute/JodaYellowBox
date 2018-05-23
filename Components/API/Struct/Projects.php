<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Struct;

use Doctrine\Common\Collections\ArrayCollection;

class Projects implements \Iterator
{
    /** @var ArrayCollection|Project[] */
    private $projects;

    private $position = 0;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function add(Project $project)
    {
        $this->projects->add($project);
    }

    public function current(): Project
    {
        return $this->projects[$this->position];
    }

    public function next()
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->projects[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}
