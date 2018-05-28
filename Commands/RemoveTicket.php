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
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $ticketService = $this->container->get('joda_yellow_box.services.ticket');
        $ticket = $ticketService->getTicket($name);

        if (!$ticket) {
            $io->error("Ticket $name could not be found!");
        }

        $em = $this->container->get('models');
        $em->remove($ticket);
        $em->flush();

        $io->success('Ticket was successfully deleted');
    }
}
