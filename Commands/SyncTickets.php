<?php

declare(strict_types=1);

namespace JodaYellowBox\Commands;

use JodaYellowBox\Components\API\ApiException;
use JodaYellowBox\Services\ReleaseManagerInterface;
use JodaYellowBox\Services\TicketManagerInterface;
use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class SyncTickets extends ShopwareCommand
{
    /** @var TicketManagerInterface */
    protected $ticketManager;

    /** @var ReleaseManagerInterface */
    protected $releaseManager;

    /**
     * @param string|null             $name
     * @param ReleaseManagerInterface $releaseManager
     * @param TicketManagerInterface  $ticketManager
     */
    public function __construct(
        string $name = null,
        ReleaseManagerInterface $releaseManager,
        TicketManagerInterface $ticketManager
    ) {
        parent::__construct($name);

        $this->releaseManager = $releaseManager;
        $this->ticketManager = $ticketManager;
    }

    protected function configure()
    {
        $this->addOption('release', 'r', InputOption::VALUE_REQUIRED, 'Sync tickets for this release name', 'current');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $releaseName = $input->getOption('release');
        if ($releaseName === 'current') {
            $release = $this->releaseManager->getCurrentRelease();
        } else {
            $release = $this->releaseManager->getReleaseByName($releaseName);
        }

        if (!$release) {
            $io->error('Release `' . $releaseName . '` could not be found');

            return;
        }

        try {
            $this->ticketManager->syncTicketsFromRemote($release);
        } catch (ApiException $e) {
            $io->error($e->getMessage());

            return;
        }

        $io->success('Tickets were successfully synced');
    }
}
