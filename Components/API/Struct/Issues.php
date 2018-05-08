<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Struct;

use Doctrine\Common\Collections\ArrayCollection;

class Issues implements \Iterator
{
    /** @var ArrayCollection|Issue[] */
    private $issues;

    private $position = 0;

    public function __construct()
    {
        $this->issues = new ArrayCollection();
    }

    public function add(Issue $issue)
    {
        $this->issues->add($issue);
    }

    public function current()
    {
        $this->issues[$this->position];
    }

    public function next()
    {
        ++$this->position;
    }

    public function key()
    {
        $this->position;
    }

    public function valid()
    {
        return isset($this->issues[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}
