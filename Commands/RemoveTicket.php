<?php

declare(strict_types=1);

namespace JodaYellowBox\Commands;

use JodaYellowBox\Components\Ticket\TicketNotExistException;
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
        $ticketDestroyer = $this->container->get('joda_yellow_box.ticket_destroyer');

        $io = new SymfonyStyle($input, $output);

        try {
            $ticketDestroyer->deleteTicket($input->getArgument('name'));
        } catch (TicketNotExistException $e) {
            $io->error($e->getMessage());

            return null;
        }

        $io->success('Ticket was successfully deleted');
    }
}
