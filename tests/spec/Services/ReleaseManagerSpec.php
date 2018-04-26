<?php

namespace spec\JodaYellowBox\Services;

use JodaYellowBox\Components\Config\PluginConfigInterface;
use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\ReleaseRepository;
use JodaYellowBox\Services\ReleaseManager;
use JodaYellowBox\Services\ReleaseManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReleaseManagerSpec extends ObjectBehavior
{
    public function let(ReleaseRepository $releaseRepository, PluginConfigInterface $pluginConfig)
    {
        $this->beConstructedWith($releaseRepository, $pluginConfig);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ReleaseManager::class);
        $this->shouldBeAnInstanceOf(ReleaseManagerInterface::class);
    }

    public function it_can_return_current_release_name(
        ReleaseRepository $releaseRepository,
        PluginConfigInterface $pluginConfig,
        Release $release
    ) {
        // Test release by name
        $pluginConfig->getReleaseToDisplay()->shouldBeCalled()->willReturn('Release 123');
        $this->getCurrentReleaseName()->shouldBe('Release 123');

        // Test latest release with no release found
        $pluginConfig->getReleaseToDisplay()->shouldBeCalled()->willReturn('latest');
        $releaseRepository->findLatestRelease()->shouldBeCalled()->willReturn(null);
        $this->getCurrentReleaseName()->shouldBe('latest');

        // Test latest release with release found
        $pluginConfig->getReleaseToDisplay()->shouldBeCalled()->willReturn('latest');
        $releaseRepository->findLatestRelease()->shouldBeCalled()->willReturn($release);
        $release->getName()->shouldBeCalled()->willReturn('New Release');
        $this->getCurrentReleaseName()->shouldBe('New Release');
    }

    public function it_can_return_current_release(
        ReleaseRepository $releaseRepository,
        PluginConfigInterface $pluginConfig
    ) {
        // Test latest release
        $pluginConfig->getReleaseToDisplay()->shouldBeCalled()->willReturn('latest');
        $releaseRepository->findLatestRelease()->shouldBeCalled();
        $this->getCurrentRelease();

        // Test release by name
        $pluginConfig->getReleaseToDisplay()->shouldBeCalled()->willReturn('Release 123');
        $releaseRepository->findReleaseByName('Release 123')->shouldBeCalled();
        $this->getCurrentRelease();
    }
}
