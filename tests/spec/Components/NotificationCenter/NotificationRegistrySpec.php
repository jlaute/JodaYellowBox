<?php

namespace spec\JodaYellowBox\Components\NotificationCenter;

use JodaYellowBox\Components\Config\PluginConfig;
use JodaYellowBox\Components\NotificationCenter\NotificationRegistry;
use JodaYellowBox\Components\NotificationCenter\Notifications\NotificationInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NotificationRegistrySpec extends ObjectBehavior
{
    public function let(PluginConfig $pluginConfig)
    {
        $this->beConstructedWith($pluginConfig);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(NotificationRegistry::class);
    }

    public function it_can_add_notifications(PluginConfig $pluginConfig, NotificationInterface $notification)
    {
        $pluginConfig->get(Argument::is('JodaYellowBoxNotifications'))->shouldBeCalled()->willReturn(['notification_id']);
        $this->add($notification, 'notification_id');

        $this->getAll()->shouldReturn([$notification]);
    }
}
