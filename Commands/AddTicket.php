<?php

namespace JodaYellowBox\Commands;

use JodaYellowBox\Models\Ticket;
use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class AddTicket extends ShopwareCommand
{
    protected function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the ticket');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $ticket = $this->container->get('models')->getRepository(Ticket::class)->find(1);
        $config = $this->container->getParameter('joda_yellow_box.sm.configs');

        //$stateMachine = new \SM\StateMachine\StateMachine($ticket, $config);

        //$res = $stateMachine->can('approve');
        //$res = $stateMachine->can('reopen');

        // Get the factory
        $factory = $this->container->get('sm.factory');

        // Get the state machine for this object, and graph called "simple"
        $articleSM = $factory->get($ticket);

        $canApprove = $articleSM->can('approve');
        $canReject = $articleSM->can('reject');
        $canReopen = $articleSM->can('reopen');
        $poss = $articleSM->getPossibleTransitions();
        $success = $articleSM->apply('approve');

        $io->success('Ticket was successfully created');
    }
}
