<?php declare(strict_types=1);

namespace JodaYellowBox\Subscriber;

use Enlight\Event\SubscriberInterface;
use JodaYellowBox\Components\API\ApiException;
use JodaYellowBox\Services\ReleaseManagerInterface;
use JodaYellowBox\Services\TicketManager;
use JodaYellowBox\Services\TicketManagerInterface;

class Cron implements SubscriberInterface
{
    /** @var TicketManager */
    protected $ticketManager;

    /** @var ReleaseManagerInterface */
    protected $releaseManager;

    public function __construct(ReleaseManagerInterface $releaseManager, TicketManagerInterface $ticketManager)
    {
        $this->releaseManager = $releaseManager;
        $this->ticketManager = $ticketManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            'JodaYellowBox_CronJob_ApiSync' => 'onApiSyncCronjob',
        ];
    }

    /**
     * Sync releases and Tickets from remote
     *
     * @param \Shopware_Components_Cron_CronJob $cronJob
     */
    public function onApiSyncCronjob(\Shopware_Components_Cron_CronJob $cronJob)
    {
        try {
            $this->releaseManager->syncReleasesFromRemote();

            $currentRelease = $this->releaseManager->getCurrentRelease();
            $this->ticketManager->syncTicketsFromRemote($currentRelease);
        } catch (ApiException $e) {
            $cronJob->setReturn('Error: ' . $e->getMessage());
        }
    }
}
