<?php

declare(strict_types=1);

namespace JodaYellowBox\Commands;

use JodaYellowBox\Services\TicketServiceInterface;
use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class ApiSync extends ShopwareCommand
{
    /** @var TicketServiceInterface */
    protected $ticketService;

    /**
     * @param string|null            $name
     * @param TicketServiceInterface $ticketService
     */
    public function __construct(
        string $name = null,
        TicketServiceInterface $ticketService
    ) {
        parent::__construct($name);

        $this->ticketService = $ticketService;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $this->ticketService->syncRemoteData();

        $io->success('Tickets were successfully synced');
    }
}
