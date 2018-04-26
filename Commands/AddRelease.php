<?php

declare(strict_types=1);

namespace JodaYellowBox\Commands;

use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\Ticket;
use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class AddRelease extends ShopwareCommand
{
    protected function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the Release')
            ->addOption('releasedate', 'r', InputOption::VALUE_REQUIRED, 'Release Date')
            ->addOption('tickets', 't', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Ticket names to add to release');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $ticketManager = $this->getContainer()->get('joda_yellow_box.services.ticket_manager');

        $releasedate = new \DateTime();
        if ($input->getOption('releasedate')) {
            $releasedate = strtotime($input->getOption('releasedate'));
        }

        $release = new Release($input->getArgument('name'), $releasedate);
        foreach ($input->getOption('tickets') as $ticketName) {
            $ticket = $ticketManager->getTicket($ticketName);
            if (!$ticket) {
                if (!$io->confirm("Ticket '$ticketName' does not exist. Create it for this release?")) {
                    continue;
                }

                $ticket = new Ticket($ticketName);
            }
            $release->addTicket($ticket);
        }

        $modelManager = $this->getContainer()->get('models');
        $modelManager->persist($release);
        $modelManager->flush();

        $io->success('Release was successfully created');
    }
}
