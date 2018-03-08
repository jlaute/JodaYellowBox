<?php

namespace JodaYellowBox\Tests;

use JodaYellowBox\JodaYellowBox as Plugin;
use Shopware\Components\Test\Plugin\TestCase;

class PluginTest extends TestCase
{
    protected static $ensureLoadedPlugins = [
        'JodaYellowBox' => []
    ];

    public function testCanCreateInstance()
    {
        /** @var Plugin $plugin */
        $plugin = Shopware()->Container()->get('kernel')->getPlugins()['JodaYellowBox'];

        $this->assertInstanceOf(Plugin::class, $plugin);
    }
}
