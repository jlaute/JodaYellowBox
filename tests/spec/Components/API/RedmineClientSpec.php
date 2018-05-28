<?php

namespace spec\JodaYellowBox\Components\API;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Message\ResponseInterface;
use JodaYellowBox\Components\API\Client\AbstractClient;
use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\API\RedmineClient;
use JodaYellowBox\Components\API\Struct\Project;
use JodaYellowBox\Components\API\Struct\Version;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RedmineClientSpec extends ObjectBehavior
{
    public function let(GuzzleClientInterface $client)
    {
        $this->beConstructedWith($client, 'apiKey', 'http://fakeUrl.de');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RedmineClient::class);
        $this->shouldHaveType(AbstractClient::class);
        $this->shouldBeAnInstanceOf(ClientInterface::class);
    }

    public function it_can_get_projects(GuzzleClientInterface $client, ResponseInterface $response)
    {
        $client->get(Argument::type('string'), Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn($response);

        $response->json()->shouldBeCalled()->willReturn(['projects' => []]);

        $this->getProjects();
    }

    public function it_can_get_all_issue_statuses(GuzzleClientInterface $client, ResponseInterface $response)
    {
        $client->get(Argument::type('string'), Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn($response);

        $response->json()->shouldBeCalled()->willReturn(['issue_statuses' => []]);

        $this->getAllIssueStatuses();
    }

    public function it_can_get_all_issues(GuzzleClientInterface $client, ResponseInterface $response)
    {
        $client->get(Argument::type('string'), Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn($response);

        $response->json()->shouldBeCalled()->willReturn([
            'issues' => [],
            'total_count' => 1,
            'limit' => 100,
            'offset' => 0
        ]);

        $this->getAllIssues();
    }

    public function it_can_get_issues_by_project(
        GuzzleClientInterface $client,
        ResponseInterface $response,
        Project $project
    ) {
        $client->get(Argument::type('string'), Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn($response);

        $response->json()->shouldBeCalled()->willReturn([
            'issues' => [],
            'total_count' => 1,
            'limit' => 100,
            'offset' => 0
        ]);

        $this->getIssuesByProject($project);
    }

    public function it_can_receive_issues_by_version(
        GuzzleClientInterface $client,
        ResponseInterface $response,
        Version $version
    ) {
        $client->get(Argument::type('string'), Argument::type('array'))->shouldBeCalled()->willReturn($response);
        $jsonContent = include __DIR__ . '/data/2-issues-2-limit.php';

        $response->json()->shouldBeCalled()->willReturn($jsonContent);

        $this->getIssuesByVersion($version, null, 0, 2);
    }

    public function it_can_get_versions_in_project(
        GuzzleClientInterface $client,
        ResponseInterface $response,
        Project $project
    ) {
        $client->get(Argument::type('string'), Argument::type('array'))
            ->shouldBeCalled()
            ->willReturn($response);

        $response->json()->shouldBeCalled()->willReturn(['versions' => []]);

        $this->getVersionsInProject($project);
    }
}
