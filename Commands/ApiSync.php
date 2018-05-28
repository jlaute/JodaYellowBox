<?php

declare(strict_types=1);

namespace JodaYellowBox\Commands;

use JodaYellowBox\Services\RemoteApiClientInterface;
use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class ApiSync extends ShopwareCommand
{
    /** @var RemoteApiClientInterface */
    protected $remoteApiClient;

    /**
     * @param string|null              $name
     * @param RemoteApiClientInterface $remoteApiClient
     */
    public function __construct(
        string $name = null,
        RemoteApiClientInterface $remoteApiClient
    ) {
        parent::__construct($name);

        $this->remoteApiClient = $remoteApiClient;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $this->remoteApiClient->fetchData();

        $io->success('Tickets were successfully synced');
    }
}
