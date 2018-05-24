<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Struct;

use Doctrine\Common\Collections\ArrayCollection;

class IssueStatuses implements \Iterator
{
    /** @var ArrayCollection|IssueStatus[] */
    private $issueStatuses;

    private $position = 0;

    public function __construct()
    {
        $this->issueStatuses = new ArrayCollection();
    }

    public function add(IssueStatus $project)
    {
        $this->issueStatuses->add($project);
    }

    public function current(): IssueStatus
    {
        return $this->issueStatuses[$this->position];
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
        return isset($this->issueStatuses[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}
