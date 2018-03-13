<?php

namespace JodaYellowBox\Commands;

use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author    Jörg Lautenschlager <jl@solutiondrive.de>
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
        $io->success('Ticket was successfully created');
    }
}
