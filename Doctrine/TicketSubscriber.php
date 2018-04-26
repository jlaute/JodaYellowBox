<?php

declare(strict_types=1);

namespace JodaYellowBox\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use JodaYellowBox\Models\Ticket;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class TicketSubscriber implements EventSubscriber
{
    const STATE_PROPERTY = 'state';

    public function getSubscribedEvents()
    {
        return [
            Events::preUpdate,
        ];
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $object = $args->getObject();

        if (!$object instanceof Ticket) {
            return;
        }

        if (!$args->hasChangedField(self::STATE_PROPERTY)) {
            return;
        }

        $oldState = $args->getOldValue(self::STATE_PROPERTY);
        $newState = $args->getNewValue(self::STATE_PROPERTY);
        $date = new \DateTime();

        // We can not use doctrine ORM in here!
        $args->getEntityManager()->getConnection()->insert('s_plugin_yellow_box_ticket_history', [
            'ticket_id' => $object->getId(),
            'old_state' => $oldState,
            'new_state' => $newState,
            'date' => $date->format('Y-m-d H:i:s'),
        ]);
    }
}
