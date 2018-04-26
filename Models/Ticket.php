<?php

declare(strict_types=1);

namespace JodaYellowBox\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Shopware\Components\Model\ModelEntity;
use SM\StateMachine\StateMachineInterface;

/**
 * @ORM\Table(name="s_plugin_yellow_box_ticket")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Ticket extends ModelEntity
{
    const STATE_OPEN = 'open';
    const STATE_APPROVED = 'approved';
    const STATE_REJECTED = 'rejected';
    const STATE_REOPENED = 'reopened';

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
     * @ORM\Column(name="number", type="string", nullable=true, unique=true)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", nullable=false)
     */
    private $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="changed_at", type="datetime", nullable=true)
     */
    private $changedAt;

    /**
     * @var Collection[Release]
     *
     * @ORM\ManyToMany(targetEntity="JodaYellowBox\Models\Release", mappedBy="tickets")
     */
    private $releases;

    /**
     * @var array
     */
    private $possibleTransitions = [];

    /**
     * @var StateMachineInterface
     */
    private $stateMachine;

    /**
     * @param string                $name
     * @param string                $number
     * @param string                $description
     * @param StateMachineInterface $stateMachine
     */
    public function __construct(
        string $name,
        string $number = '',
        string $description = '',
        StateMachineInterface $stateMachine = null
    ) {
        $this->createdAt = new \DateTime();
        $this->state = self::STATE_OPEN;
        $this->name = $name;
        $this->number = $number;
        $this->description = $description;
        $this->stateMachine = $stateMachine;
        $this->releases = new ArrayCollection();
    }

    /**
     * @ORM\PostLoad
     */
    public function onPostLoad()
    {
        $this->updatePossibleTransitions();
    }

    /**
     * @ORM\PostUpdate
     */
    public function onPostUpdate()
    {
        $this->updatePossibleTransitions();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->changedAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id ?: 0;
    }

    /**
     * @param string $number
     */
    public function setNumber(string $number)
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number ?: '';
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description ?: '';
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state)
    {
        $this->state = $state;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return clone $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getChangedAt(): \DateTime
    {
        return clone $this->changedAt;
    }

    /**
     * @return array
     */
    public function getPossibleTransitions(): array
    {
        return $this->possibleTransitions;
    }

    /**
     * @param array $transitions
     */
    public function setPossibleTransitions(array $transitions)
    {
        $this->possibleTransitions = $transitions;
    }

    /**
     * @param Release $release
     */
    public function addToRelease(Release $release)
    {
        if (!$this->releases->contains($release)) {
            $this->releases->add($release);
        }
    }

    /**
     * @param Release $release
     */
    public function removeFromRelease(Release $release)
    {
        $this->releases->removeElement($release);
    }

    /**
     * @return Collection[Release]
     */
    public function getReleases(): Collection
    {
        return $this->releases;
    }

    protected function updatePossibleTransitions()
    {
        if (!$this->stateMachine) {
            // @TODO: this would be better if does not depend on Shopware Container
            $stateMachineFactory = Shopware()->Container()->get('joda_yellow_box.sm.factory');
            /* @var StateMachineInterface $stateMachine */
            $this->stateMachine = $stateMachineFactory->get($this);
        }

        $this->possibleTransitions = $this->stateMachine->getPossibleTransitions();
    }
}
