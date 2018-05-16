<?php declare(strict_types=1);

namespace JodaYellowBox\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Shopware\Components\Model\ModelEntity;

/**
 * @ORM\Table(name="s_plugin_yellow_box_release")
 * @ORM\Entity
 */
class Release extends ModelEntity
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false, unique=true)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="release_date", type="datetime", nullable=false)
     */
    private $releaseDate;

    /**
     * @var Collection[Ticket]
     *
     * @ORM\ManyToMany(targetEntity="JodaYellowBox\Models\Ticket", inversedBy="releases", cascade={"persist"})
     * @ORM\JoinTable(name="s_plugin_yellow_box_releases_tickets")
     */
    private $tickets;

    /**
     * @param string    $name
     * @param \DateTime $releaseDate
     */
    public function __construct(string $name, \DateTime $releaseDate = null)
    {
        $this->name = $name;
        $this->releaseDate = $releaseDate ?: new \DateTime();
        $this->tickets = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id ?: 0;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return \DateTime
     */
    public function getReleaseDate(): \DateTime
    {
        return clone $this->releaseDate;
    }

    /**
     * @param Ticket $ticket
     */
    public function addTicket(Ticket $ticket)
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->addToRelease($this);
        }
    }

    /**
     * @param array $tickets
     */
    public function addTickets(array $tickets)
    {
        foreach ($tickets as $ticket) {
            $this->addTicket($ticket);
        }
    }

    /**
     * @param Ticket $ticket
     */
    public function removeTicket(Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
        $ticket->removeFromRelease($this);
    }

    /**
     * @return Collection[Ticket]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }
}
