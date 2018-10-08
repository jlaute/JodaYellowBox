<?php

namespace spec\JodaYellowBox\Components\NotificationCenter;

use JodaYellowBox\Components\NotificationCenter\NotificationCenter;
use JodaYellowBox\Components\NotificationCenter\NotificationCenterInterface;
use JodaYellowBox\Components\NotificationCenter\NotificationRegistry;
use JodaYellowBox\Components\NotificationCenter\Notifications\NotificationInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @package spec\JodaYellowBox\Components\NotificationCenter
 * @mixin NotificationCenter
 */
class NotificationCenterSpec extends ObjectBehavior
{
    public function let(NotificationRegistry $notificationRegistry)
    {
        $this->beConstructedWith($notificationRegistry);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(NotificationCenter::class);
        $this->shouldImplement(NotificationCenterInterface::class);
    }

    public function it_can_send_notifications(
        NotificationRegistry $notificationRegistry,
        NotificationInterface $emailNotification,
        NotificationInterface $telegramNotification
    ) {
        $notificationRegistry->getAll()->shouldBeCalled()->willReturn([$emailNotification, $telegramNotification]);
        $emailNotification->send(Argument::is('asdf'))->shouldBeCalled();
        $telegramNotification->send(Argument::is('asdf'))->shouldBeCalled();

        $this->notify('asdf');
    }
}
