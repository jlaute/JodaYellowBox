<?php

declare(strict_types=1);

use SM\StateMachine\StateMachine;
use SM\Factory\Factory as StateManagerFactory;
use JodaYellowBox\Subscriber\TicketModifier;
use Shopware\Components\Test\Plugin\TestCase;

class TicketModifierTest extends TestCase
{
    /**
     * @var TicketModifier
     */
    protected $modifierSubscriber;

    /**
     * @var array
     */
    protected static $ensureLoadedPlugins = [
        'JodaYellowBox' => []
    ];

    public function setUp()
    {
        $this->modifierSubscriber = new TicketModifier(
            $this->getStateMaschineFactoryMock()
        );
    }

    public function tearDown()
    {
        $this->modifierSubscriber = null;
    }

    public function testFilterTicket()
    {
        $args = new Enlight_Event_EventArgs();
        $args->setReturn([
            'test'
        ]);

        $ticket = $this->modifierSubscriber->onFilterTicket($args);

        $this->assertArrayHasKey('possibleTransitions', $ticket);
        $this->assertSame($ticket, [
            'test', 'possibleTransitions' => [
                'trans1',
                'trans2'
            ]
        ]);
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getStateMaschineFactoryMock()
    {
        $stateMaschineFactory = $this->createMock(StateManagerFactory::class);
        $stateMaschineFactory
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->getStateMaschineMock());

        return $stateMaschineFactory;
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getStateMaschineMock()
    {
        $stateMaschine = $this->createMock(StateMachine::class);
        $stateMaschine
            ->method('getPossibleTransitions')
            ->willReturn([
                'trans1',
                'trans2'
            ]);

        return $stateMaschine;
    }
}
