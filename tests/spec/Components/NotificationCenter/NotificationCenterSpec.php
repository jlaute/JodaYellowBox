<?php

namespace spec\JodaYellowBox\Components\NotificationCenter;

use JodaYellowBox\Components\NotificationCenter\NotificationCenter;
use JodaYellowBox\Components\NotificationCenter\NotificationCenterInterface;
use JodaYellowBox\Components\NotificationCenter\Notifications\NotificationInterface;
use PhpSpec\ObjectBehavior;

/**
 * @package spec\JodaYellowBox\Components\NotificationCenter
 * @mixin NotificationCenter
 */
class NotificationCenterSpec extends ObjectBehavior
{
    function let()
    {
        $configuration = [
            'joda_yellow_box.notification.email'
        ];

        $this->beConstructedWith($configuration);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NotificationCenter::class);
        $this->shouldImplement(NotificationCenterInterface::class);
    }

    function it_able_to_register_notifications()
    {
        $this
            ->getNotification('joda_yellow_box.notification.email')
            ->shouldReturnAnInstanceOf(NotificationInterface::class);
    }

    function it_is_able_to_check_existing_notifications(NotificationInterface $notification)
    {
        $this->existsNotification('test')->shouldReturn(false);
        $this->addNotification('test', $notification);
        $this->existsNotification('test')->shouldReturn(true);
    }

    function it_is_able_to_get_set_notification(NotificationInterface $notification)
    {
        $this->getNotification('test')->shouldReturn(null);
        $this->addNotification('test', $notification);
        $this->getNotification('test')->shouldReturnAnInstanceOf(NotificationInterface::class);
    }

    function it_is_able_to_remove_notification()
    {
        $this->removeNotification('joda_yellow_box.notification.email');
        $this->getNotification('joda_yellow_box.notification.email')->shouldReturn(null);
    }
}
