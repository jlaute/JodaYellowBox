<?php

declare(strict_types=1);

namespace JodaYellowBox\Commands;

use JodaYellowBox\Components\API\ApiException;
use JodaYellowBox\Services\ReleaseManagerInterface;
use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class SyncReleases extends ShopwareCommand
{
    /** @var ReleaseManagerInterface */
    protected $releaseManager;

    /**
     * @param string|null             $name
     * @param ReleaseManagerInterface $releaseManager
     */
    public function __construct(string $name = null, ReleaseManagerInterface $releaseManager)
    {
        parent::__construct($name);

        $this->releaseManager = $releaseManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->releaseManager->syncReleasesFromRemote();
        } catch (ApiException $e) {
            $io->error($e->getMessage());

            return;
        }

        $io->success('Releases were successfully synced');
    }
}
