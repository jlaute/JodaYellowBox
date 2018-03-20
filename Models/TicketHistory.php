<?php
declare(strict_types=1);

namespace JodaYellowBox\Models;

use Doctrine\ORM\Mapping as ORM;
use Shopware\Components\Model\ModelEntity;

/**
 * @ORM\Table(name="s_plugin_yellow_box_ticket_history")
 * @ORM\Entity
 */
class TicketHistory extends ModelEntity
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="ticket_id", type="integer", nullable=false)
     */
    private $ticketId;

    /**
     * @var string
     *
     * @ORM\Column(name="old_state", type="string", nullable=false)
     */
    private $oldState;

    /**
     * @var string
     *
     * @ORM\Column(name="new_state", type="string", nullable=false)
     */
    private $newState;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @param int    $ticketId
     * @param string $oldState
     * @param string $newState
     */
    public function __construct(int $ticketId, string $oldState, string $newState)
    {
        $this->ticketId = $ticketId;
        $this->oldState = $oldState;
        $this->newState = $newState;
        $this->date = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTicketId(): int
    {
        return $this->ticketId;
    }

    public function getOldState(): string
    {
        return $this->oldState;
    }

    public function getNewState(): string
    {
        return $this->newState;
    }

    public function getDate(): \DateTime
    {
        return clone $this->date;
    }
}
