<?php
declare(strict_types=1);

namespace spec\JodaYellowBox\Models;

use JodaYellowBox\Models\Release;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use JodaYellowBox\Models\Ticket;
use Shopware\Components\Model\ModelEntity;
use SM\StateMachine\StateMachineInterface;

/**
 * @mixin \JodaYellowBox\Models\Ticket
 */
class TicketSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('name', 'number', 'description');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Ticket::class);
        $this->shouldHaveType(ModelEntity::class);
    }

    public function it_has_a_created_datetime_by_default()
    {
        $this->getCreatedAt()->shouldHaveType(\DateTime::class);
    }

    public function it_can_have_a_number()
    {
        $this->getNumber()->shouldReturn('number');
        $this->setNumber('ASDF-123');
        $this->getNumber()->shouldReturn('ASDF-123');
    }

    public function it_can_have_a_name()
    {
        $this->getName()->shouldReturn('name');
        $this->setName('Ticket for testing');
        $this->getName()->shouldReturn('Ticket for testing');
    }

    public function it_can_have_a_description()
    {
        $this->getDescription()->shouldReturn('description');
        $this->setDescription('This is a ticket description');
        $this->getDescription()->shouldReturn('This is a ticket description');
    }

    public function it_has_open_state_by_default()
    {
        $this->getState()->shouldReturn(Ticket::STATE_OPEN);
    }

    public function it_can_have_releases(Release $release, Release $release2)
    {
        $this->addToRelease($release);
        $this->getReleases()->contains($release)->shouldReturn(true);

        $this->addToRelease($release2);
        $this->getReleases()->contains($release2)->shouldReturn(true);
    }

    public function it_can_be_removed_from_release(Release $release)
    {
        $this->addToRelease($release);
        $this->getReleases()->contains($release)->shouldReturn(true);

        $this->removeFromRelease($release);
        $this->getReleases()->contains($release)->shouldReturn(false);
    }
}
