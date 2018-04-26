<?php

namespace spec\JodaYellowBox\Services;

use JodaYellowBox\Models\ReleaseRepository;
use JodaYellowBox\Services\ReleaseManager;
use JodaYellowBox\Services\ReleaseManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReleaseManagerSpec extends ObjectBehavior
{
    public function let(ReleaseRepository $releaseRepository)
    {
        $this->beConstructedWith($releaseRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ReleaseManager::class);
        $this->shouldBeAnInstanceOf(ReleaseManagerInterface::class);
    }
}
