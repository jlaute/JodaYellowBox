<?php

namespace spec\JodaYellowBox\Components\Config;

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
            'JodaYellowBoxMaxWidth' => 100
        ]);

        $this->beConstructedWith('JodaYellowBox', $configReader);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PluginConfig::class);
    }

    function it_is_plugin_config()
    {
        $this->shouldImplement(PluginConfigInterface::class);
    }

    function it_is_able_to_set_a_custom_single_config_value()
    {
        $this->set('test', 'test');
        $this->get('test')->shouldReturn('test');
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
}
