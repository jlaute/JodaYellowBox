<?php

declare(strict_types=1);

use JodaYellowBox\Components\Config\PluginConfigInterface;
use JodaYellowBox\Subscriber\Assets;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Shopware\Components\Test\Plugin\TestCase;

class AssetsTest extends TestCase
{
    /**
     * @var Assets
     */
    protected $assetSubscriber;

    /**
     * @var array
     */
    protected static $ensureLoadedPlugins = [
        'JodaYellowBox' => [],
    ];
    /**
     * @var vfsStreamDirectory
     */
    private $fileSystem;

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
                    'jquery.yellow-ui.js',
                    'jquery.unused.js',
                ],
            ],
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
            $this->fileSystem->url() . '/js/jquery-ui.js',
            $this->fileSystem->url() . '/js/jquery.confirmation.js',
        ], $this->assetSubscriber->onCollectJavascript()->toArray());
    }
}
