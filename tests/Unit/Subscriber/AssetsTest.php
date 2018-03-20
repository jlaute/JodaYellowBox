<?php

declare(strict_types=1);

use JodaYellowBox\Subscriber\Assets;
use JodaYellowBox\Components\Config\PluginConfigInterface;
use Shopware\Components\Test\Plugin\TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class AssetsTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $fileSystem;

    /**
     * @var Assets
     */
    protected $assetSubscriber;

    /**
     * @var array
     */
    protected static $ensureLoadedPlugins = [
        'JodaYellowBox' => []
    ];

    public function setUp()
    {
        $directory = [
            'testPlugin' => [
                'less' => [
                    'all.less',
                    'mixins.less',
                    'modules.less',
                ],
                'js' => [
                    'jquery.yellow-box.js',
                    'jquery.unused.js',
                ]
            ]
        ];

        $this->fileSystem = vfsStream::setup('root', 444, $directory);

        $jsDir = $this->fileSystem->url() . '/js';
        $lessDir = $this->fileSystem->url() . '/less';
        $configMock = $this->getMockBuilder(PluginConfigInterface::class)->getMock();

        $this->assetSubscriber = new Assets($lessDir, $jsDir, $configMock);
    }

    public function tearDown()
    {
        $this->fileSystem = null;
        $this->assetSubscriber = null;
    }

    public function testCollectingLessFiles()
    {
        $this->assertSame([
            $this->fileSystem->url() . '/less/all.less',
        ], $this->assetSubscriber->onCollectLess()->getFiles());
    }

    public function testCollectingJavascriptFiles()
    {
        $this->assertSame([
            $this->fileSystem->url() . '/js/jquery.yellow-box.js',
        ], $this->assetSubscriber->onCollectJavascript()->toArray());
    }
}
