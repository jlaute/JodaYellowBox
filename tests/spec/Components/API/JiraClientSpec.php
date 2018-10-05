<?php

namespace spec\JodaYellowBox\Components\API;

use GuzzleHttp\ClientInterface as GuzzleClient;
use GuzzleHttp\Message\ResponseInterface;
use JodaYellowBox\Components\API\Client\AbstractClient;
use JodaYellowBox\Components\API\JiraClient;
use JodaYellowBox\Components\API\Struct\IssueStatus;
use JodaYellowBox\Components\API\Struct\Project;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JiraClientSpec extends ObjectBehavior
{
    public function let(GuzzleClient $client)
    {
        $key = '124';
        $pw = '444';
        $this->beConstructedWith($client, $key, $pw);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(JiraClient::class);
        $this->shouldHaveType(AbstractClient::class);
    }

    public function it_can_get_issues_by_project(
        GuzzleClient $client,
        Project $project,
        ResponseInterface $response
    ) {
        $client->get(Argument::type('string'), Argument::type('array'))->shouldBeCalled()->willReturn($response);
        $response->json()->shouldBeCalled()->willReturn([
            'startAt' => 0,
            'maxResults' => 100,
            'total' => 0,
            'issues' => []
        ]);

        $this->getIssuesByProject($project);
    }
}
