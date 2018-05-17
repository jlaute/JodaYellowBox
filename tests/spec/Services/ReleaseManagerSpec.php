<?php

namespace spec\JodaYellowBox\Services;

use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\ReleaseRepository;
use JodaYellowBox\Services\ReleaseManager;
use JodaYellowBox\Services\ReleaseManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReleaseManagerSpec extends ObjectBehavior
{
    public function let(ReleaseRepository $releaseRepository, ClientInterface $clientInterface)
    {
        $this->beConstructedWith($releaseRepository, $clientInterface, 'Release 123');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ReleaseManager::class);
        $this->shouldBeAnInstanceOf(ReleaseManagerInterface::class);
    }

    public function it_can_return_current_release_name()
    {
        $this->getCurrentReleaseName()->shouldBe('Release 123');
    }

    public function it_will_return_latest_release_name_when_no_release_can_be_found(
        ReleaseRepository $releaseRepository,
        ClientInterface $clientInterface
    ) {
        $this->beConstructedWith($releaseRepository, $clientInterface, 'latest');
        $releaseRepository->findLatestRelease()->shouldBeCalled()->willReturn(null);
        $this->getCurrentReleaseName()->shouldBe('latest');
    }

    public function it_will_return_the_latest_release_name(
        ReleaseRepository $releaseRepository,
        ClientInterface $clientInterface,
        Release $release
    ) {
        $this->beConstructedWith($releaseRepository, $clientInterface, 'latest');
        $releaseRepository->findLatestRelease()->shouldBeCalled()->willReturn($release);
        $release->getName()->shouldBeCalled()->willReturn('New Release');
        $this->getCurrentReleaseName()->shouldBe('New Release');
    }

    public function it_can_return_current_release(
        ReleaseRepository $releaseRepository,
        ClientInterface $clientInterface
    ) {
        $this->beConstructedWith($releaseRepository, $clientInterface, 'latest');
        $releaseRepository->findLatestRelease()->shouldBeCalled();
        $this->getCurrentRelease();
    }

    public function it_can_return_release_by_name(ReleaseRepository $releaseRepository)
    {
        $releaseRepository->findReleaseByName('Release 123')->shouldBeCalled();
        $this->getCurrentRelease();
    }
}
