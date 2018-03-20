<?php

declare(strict_types=1);

use JodaYellowBox\Subscriber\Template;
use Shopware\Components\Test\Plugin\TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class TemplateTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $fileSystem;

    /**
     * @var Template
     */
    protected $templateSubscriber;

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
                'views' => [
                    'test.tpl',
                ]
            ]
        ];

        $this->fileSystem = vfsStream::setup('root', 444, $directory);

        $viewDir = $this->fileSystem->url() . '/views';
        $this->templateSubscriber = new Template($viewDir);
    }

    public function tearDown()
    {
        $this->fileSystem = null;
        $this->templateSubscriber = null;
    }

    public function testCollectingTemplates()
    {
        $args = new Enlight_Event_EventArgs();
        $this->templateSubscriber->onCollectedTemplates($args);

        $this->assertSame([
            $this->fileSystem->url() . '/views',
        ], $args->getReturn());
    }
}
