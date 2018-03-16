<?php

declare(strict_types=1);

namespace JodaYellowBox\Models;

use Shopware\Components\Model\ModelEntity;
use Doctrine\ORM\Mapping as ORM;
use SM\StateMachine\StateMachineInterface;

/**
 * @ORM\Table(name="s_plugin_yellow_box_ticket")
 * @ORM\Entity(repositoryClass="Repository")
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
     * @ORM\Column(name="number", type="string", nullable=true)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false)
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

    public function __construct(string $name, string $number = null, string $description = null)
    {
        $this->createdAt = new \DateTime();
        $this->state = self::STATE_OPEN;
        $this->name = $name;
        $this->number = $number;
        $this->description = $description;
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->changedAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setNumber(string $number)
    {
        $this->number = $number;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getChangedAt(): \DateTime
    {
        return $this->changedAt;
    }

    public function approve(StateMachineInterface $stateMachine)
    {
        $this->changeState($stateMachine, 'approve');
    }

    public function reject(StateMachineInterface $stateMachine)
    {
        $this->changeState($stateMachine, 'reject');
    }

    public function reopen(StateMachineInterface $stateMachine)
    {
        $this->changeState($stateMachine, 'reopen');
    }

    protected function changeState(StateMachineInterface $stateMachine, string $state)
    {
        if ($stateMachine->can($state)) {
            $stateMachine->apply($state);
        }
    }
}
