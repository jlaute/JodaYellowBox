<?php

declare(strict_types=1);

namespace JodaYellowBox\Commands;

use JodaYellowBox\Exception\TicketAlreadyExistException;
use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class AddTicket extends ShopwareCommand
{
    protected function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the ticket')
            ->addOption('number', 'nu', InputOption::VALUE_REQUIRED, 'Ticket number')
            ->addOption('description', 'd', InputOption::VALUE_REQUIRED, 'Ticket description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $ticketCreator = $this->container->get('joda_yellow_box.ticket_creator');

        try {
            $ticketCreator->createTicket($input->getArgument('name'));
        } catch (TicketAlreadyExistException $e) {
            $io->error($e->getMessage());

            return null;
        }

        $io->success('Ticket was successfully created');
    }
}
