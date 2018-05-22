<?php

namespace spec\JodaYellowBox\Services;

use JodaYellowBox\Components\API\ApiException;
use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\API\Struct\Project;
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
    public function let(ReleaseRepository $releaseRepository, ClientInterface $client)
    {
        $this->beConstructedWith($releaseRepository, $client, 'Release 123', '20');
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
        ClientInterface $client
    ) {
        $this->beConstructedWith($releaseRepository, $client, 'latest');
        $releaseRepository->findLatestRelease()->shouldBeCalled()->willReturn(null);
        $this->getCurrentReleaseName()->shouldBe('latest');
    }

    public function it_will_return_the_latest_release_name(
        ReleaseRepository $releaseRepository,
        ClientInterface $client,
        Release $release
    ) {
        $this->beConstructedWith($releaseRepository, $client, 'latest');
        $releaseRepository->findLatestRelease()->shouldBeCalled()->willReturn($release);
        $release->getName()->shouldBeCalled()->willReturn('New Release');
        $this->getCurrentReleaseName()->shouldBe('New Release');
    }

    public function it_can_return_current_release(
        ReleaseRepository $releaseRepository,
        ClientInterface $client
    ) {
        $this->beConstructedWith($releaseRepository, $client, 'latest');
        $releaseRepository->findLatestRelease()->shouldBeCalled();
        $this->getCurrentRelease();
    }

    public function it_can_return_release_by_name(ReleaseRepository $releaseRepository)
    {
        $releaseRepository->findReleaseByName('Release 123')->shouldBeCalled();
        $this->getCurrentRelease();
    }

    public function it_can_sync_releases_from_remote(
        ReleaseRepository $releaseRepository,
        ClientInterface $client
    ) {
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

        $client->getVersionsInProject(Argument::type(Project::class))->shouldBeCalled()->willReturn($versions);
        $releaseRepository->findByExternalIds(Argument::type('array'))->shouldBeCalled()->willReturn([]);
        $releaseRepository->add(Argument::type(Release::class))->shouldBeCalled();
        $releaseRepository->save()->shouldBeCalled();

        $this->syncReleasesFromRemote();
    }

    public function it_throws_an_exception_when_no_external_project_config_id_is_set(
        ReleaseRepository $releaseRepository,
        ClientInterface $client
    ) {
        $this->beConstructedWith($releaseRepository, $client, '124');
        $this->shouldThrow(ApiException::class)->during('syncReleasesFromRemote');
    }
}
