<?php

namespace spec\JodaYellowBox\Components\Config;

use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Shopware\Components\Plugin\ConfigReader;
use JodaYellowBox\Components\Config\PluginConfig;
use JodaYellowBox\Components\Config\PluginConfigInterface;

/**
 * @mixin PluginConfig
 */
class PluginConfigSpec extends ObjectBehavior
{
    function let(ConfigReader $configReader)
    {
        $configReader->getByPluginName('JodaYellowBox')->willReturn([
            'testConfig' => 'test',
            'JodaYellowBoxMaxWidth' => 100,
        ]);

        $this->beConstructedWith('JodaYellowBox', $configReader);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PluginConfig::class);
        $this->shouldHaveType(ArrayCollection::class);
    }

    function it_is_plugin_config()
    {
        $this->shouldImplement(PluginConfigInterface::class);
    }

    function it_is_able_to_set_a_custom_single_config_value()
    {
        $this->set('test', 'test');
        $this->getConfig()->shouldHaveCount(3);
    }

    function it_is_able_to_get_a_single_config_value()
    {
        $this->get('testConfig')->shouldReturn('test');
    }

    function it_is_able_to_get_whole_config()
    {
        $this->getConfig()->shouldReturn([
            'testConfig' => 'test',
            'JodaYellowBoxMaxWidth' => 100
        ]);
    }

    function it_is_able_to_get_less_configuration()
    {
        $this->getLessConfiguration()->shouldReturn([
            'JodaYellowBoxMaxWidth' => 100
        ]);
    }

    function it_is_able_to_get_release_to_display()
    {
        $this->set('JodaYellowBoxReleaseToDisplay', 'Release 1');
        $this->getReleaseToDisplay()->shouldReturn('Release 1');
    }

    function it_is_able_to_check_notifications_enabled()
    {
        $this->isNotificationEnabled()->shouldReturn(false);
        $this->set('JodaYellowBoxNotificationsEnabled', 1);
        $this->isNotificationEnabled()->shouldReturn(true);
    }

    function it_is_able_to_get_various_notifications()
    {
        $this->getNotifications()->shouldReturn([]);

        $this->set('JodaYellowBoxNotifications', ['n1', 'n2']);
        $this->getNotifications()->shouldReturn(['n1', 'n2']);
    }

    function it_is_able_to_get_notification_emails()
    {
        // empty
        $this->set('JodaYellowBoxNotificationEmails', '');
        $this->getNotificationEmails()->shouldReturn([]);
        // semicolon only
        $this->set('JodaYellowBoxNotificationEmails', ';');
        $this->getNotificationEmails()->shouldReturn([]);
        // whitespace tests
        $this->set('JodaYellowBoxNotificationEmails', 'test@joda.de; test1@joda.de  ;  test2@joda.de');
        $this->getNotificationEmails()->shouldReturn([
            'test@joda.de',
            'test1@joda.de',
            'test2@joda.de',
        ]);
    }
}
