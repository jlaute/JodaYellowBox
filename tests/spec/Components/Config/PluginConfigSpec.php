<?php

namespace spec\JodaYellowBox\Components\Config;

use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Shopware\Components\Plugin\ConfigReader;
use JodaYellowBox\Components\Config\PluginConfig;

/**
 * @mixin PluginConfig
 */
class PluginConfigSpec extends ObjectBehavior
{
    function let(ConfigReader $configReader)
    {
        $configReader->getByPluginName('JodaYellowBox')->willReturn([
            'testConfig' => 'test',
            'testConfig2' => 'test2'
        ]);

        $this->beConstructedWith('JodaYellowBox', $configReader);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PluginConfig::class);
        $this->shouldHaveType(ArrayCollection::class);
    }

    function it_is_able_to_get_a_single_config_value()
    {
        $this->get('testConfig')->shouldReturn('test');
        $this->get('testConfig2')->shouldReturn('test2');
    }
}
