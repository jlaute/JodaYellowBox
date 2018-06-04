<?php

namespace spec\JodaYellowBox\Services;

use JodaYellowBox\Components\API\Struct\Version;
use JodaYellowBox\Components\API\Struct\Versions;
use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\ReleaseRepository;
use JodaYellowBox\Services\ReleaseManager;
use JodaYellowBox\Services\ReleaseManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReleaseManagerSpec extends ObjectBehavior
{
    public function let(ReleaseRepository $releaseRepository)
    {
        $this->beConstructedWith($releaseRepository, 'Release 123');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ReleaseManager::class);
        $this->shouldBeAnInstanceOf(ReleaseManagerInterface::class);
    }

    public function it_can_return_current_release(
        ReleaseRepository $releaseRepository
    ) {
        $this->beConstructedWith($releaseRepository, 'latest');
        $releaseRepository->findLatestRelease()->shouldBeCalled();
        $this->getCurrentRelease();
    }

    public function it_can_return_release_by_name(ReleaseRepository $releaseRepository)
    {
        $releaseRepository->findReleaseByName('Release 123')->shouldBeCalled();
        $this->getCurrentRelease();
    }

    protected function mockVersions()
    {
        $versions = new Versions();
        $version1 = new Version();
        $version1->id = '12';
        $version1->name = 'name1';
        $version1->date = new \DateTime();
        $version2 = new Version();
        $version2->id = '13';
        $version2->name = 'name2';
        $version2->date = new \DateTime();

        $versions->add($version1);
        $versions->add($version2);

        return $versions;
    }

    protected function mockReleases()
    {
        $releases[] = new Release('name1', null, '12');
        $releases[] = new Release('name2', null, '11');

        return $releases;
    }
}
