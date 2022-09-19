<?php

namespace App\Entity;

use App\Repository\ExpenseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\PreUpdateEventArgs;

#[ORM\Entity(repositoryClass: ExpenseRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Expense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Location = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Detail = null;

    #[ORM\Column]
    private ?float $Amount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'Expenses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Budget $Budget = null;

    public function __construct()
    {
        $this->CreatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?string
    {
        return $this->Location;
    }

    public function setLocation(string $Location): self
    {
        $this->Location = $Location;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->Detail;
    }

    public function setDetail(?string $Detail): self
    {
        $this->Detail = $Detail;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->Amount;
    }

    public function setAmount(float $Amount): self
    {
        $this->Amount = $Amount;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(?\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeImmutable $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getBudget(): ?Budget
    {
        return $this->Budget;
    }

    public function setBudget(?Budget $Budget): self
    {
        $this->Budget = $Budget;

        return $this;
    }

    #[ORM\PrePersist]
    public function createImpactOnPrice(): void
    {
        $this->getBudget()->setAmount($this->getBudget()->getAmount()+$this->getAmount());
        $this->getBudget()->getAccount()->setBalance($this->getBudget()->getAccount()->getBalance()+$this->getAmount());

    }

    #[ORM\PreRemove]
    public function cancelImpactOnPrice(): void
    {
        $this->getBudget()->setAmount($this->getBudget()->getAmount()-$this->getAmount());
        $this->getBudget()->getAccount()->setBalance($this->getBudget()->getAccount()->getBalance()-$this->getAmount());

    }

    #[ORM\PreUpdate]
    public function updateImpactOnPriceDel(PreUpdateEventArgs $eventArgs): void
    {
        #TODO
        //On ne rentre pas dans la fonction lors d'une mise à jour d'une dépense
        /*if($eventArgs->getOldValue('Amount') != $eventArgs->getNewValue('Amount')){
            $this->getBudget()->setAmount($this->getBudget()->getAmount()-$eventArgs->getOldValue('Amount'));
            $this->getBudget()->setAmount($this->getBudget()->getAmount()+$eventArgs->getNewValue('Amount'));
        }*/
    }

}
