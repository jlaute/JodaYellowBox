<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Struct;

use Doctrine\Common\Collections\ArrayCollection;

class Trackers implements \Iterator
{
    /** @var ArrayCollection|Tracker[] */
    private $trackers;

    private $position = 0;

    public function __construct()
    {
        $this->trackers = new ArrayCollection();
    }

    public function add(Tracker $tracker)
    {
        $this->trackers->add($tracker);
    }

    public function current(): Tracker
    {
        return $this->trackers[$this->position];
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
        return isset($this->trackers[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}
