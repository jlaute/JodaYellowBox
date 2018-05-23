<?php

declare(strict_types=1);

namespace JodaYellowBox\Commands;

use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RemoveTicket extends ShopwareCommand
{
    protected function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the ticket');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ticketManager = $this->container->get('joda_yellow_box.services.ticket_manager');

        $ticket = $ticketManager->getTicket($input->getArgument('name'));
        $ticketManager->delete($ticket);

        $io = new SymfonyStyle($input, $output);
        $io->success('Ticket was successfully deleted');
    }
}
