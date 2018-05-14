<?php

declare(strict_types=1);

namespace JodaYellowBox\Commands;

use Doctrine\DBAL\DBALException;
use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LoadFixtures extends ShopwareCommand
{
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $ask = $io->confirm('Do you like to remove all of current data and insert complete new demo data?', false);

        $fixturesLoader = $this->container->get('joda_yellow_box.data_fixtures.fixtures_loader');
        $fixturesLoader->setDeleteAll($ask);

        try {
            $fixturesLoader->run();

            $io->success('Fixtures were successfully loaded');
        } catch (DBALException $ex) {
            $io->error('The demo data already included');
        }
    }
}
