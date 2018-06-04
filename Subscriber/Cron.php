<?php declare(strict_types=1);

namespace JodaYellowBox\Subscriber;

use Enlight\Event\SubscriberInterface;
use JodaYellowBox\Components\API\ApiException;
use JodaYellowBox\Services\TicketServiceInterface;

class Cron implements SubscriberInterface
{
    /** @var TicketServiceInterface */
    protected $ticketService;

    public function __construct(TicketServiceInterface $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public static function getSubscribedEvents()
    {
        return [
            'JodaYellowBox_CronJob_ApiSync' => 'onApiSyncCronjob',
        ];
    }

    /**
     * Sync releases and Tickets from remote
     * Currently only the tickets from the active release get synced. Maybe we should make this more dynamic, to sync
     * tickets from other releases too
     *
     * @param \Shopware_Components_Cron_CronJob $cronJob
     */
    public function onApiSyncCronjob(\Shopware_Components_Cron_CronJob $cronJob)
    {
        try {
            $this->ticketService->syncRemoteData();
        } catch (ApiException $e) {
            $cronJob->setReturn('Error: ' . $e->getMessage());
        }
    }
}
