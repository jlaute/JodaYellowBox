<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Struct;

use Doctrine\Common\Collections\ArrayCollection;

class Versions implements \Iterator
{
    /** @var ArrayCollection|Version[] */
    private $versions;

    private $position = 0;

    public function __construct()
    {
        $this->versions = new ArrayCollection();
    }

    public function current(): Version
    {
        return $this->versions[$this->position];
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
        return isset($this->versions[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}
