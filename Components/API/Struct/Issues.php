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

    public function current(): Issue
    {
        return $this->issues[$this->position];
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
        return isset($this->issues[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function mergeIssues(Issues $issues)
    {
        foreach ($issues as $issue) {
            $this->issues->add($issue);
        }

        return $this;
    }
}
