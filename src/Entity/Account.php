<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Name = null;

    #[ORM\Column(length: 255)]
    private ?string $Bank = null;

    #[ORM\Column]
    private ?float $Balance = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\OneToMany(mappedBy: 'Account', targetEntity: Budget::class)]
    private Collection $Budgets;

    #[ORM\ManyToOne(inversedBy: 'Accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    public function __construct()
    {
        $this->CreatedAt = new \DateTimeImmutable();
        $this->Budgets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getBank(): ?string
    {
        return $this->Bank;
    }

    public function setBank(string $Bank): self
    {
        $this->Bank = $Bank;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->Balance;
    }

    public function setBalance(float $Balance): self
    {
        $this->Balance = $Balance;

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

    /**
     * @return Collection<int, Budget>
     */
    public function getBudgets(): Collection
    {
        return $this->Budgets;
    }

    public function addBudget(Budget $budget): self
    {
        if (!$this->Budgets->contains($budget)) {
            $this->Budgets->add($budget);
            $budget->setAccount($this);
        }

        return $this;
    }

    function comparator($object1 , $object2) {
        return $object1->date > $object2->date;
    }


    public function getExpenses($date = null): array
    {
        $budgets = $this->getBudgets();
        $toReturn = [];
        foreach ($budgets as $budget) {
            foreach ($budget->getExpenses() as $expense) {
                $toReturn[] = $expense;
            }
        }
        if($date != null){
            $toReturn = array_filter($toReturn,
                function($expense) use ($date) {
                    return $expense->getDate()->format('m Y') == $date->format("m Y");
                });
        }

        usort($toReturn,
            function ($a, $b) {
                return ($b->getDate() > $a->getDate());
            });

        return $toReturn;
    }

    public function removeBudget(Budget $budget): self
    {
        if ($this->Budgets->removeElement($budget)) {
            // set the owning side to null (unless already changed)
            if ($budget->getAccount() === $this) {
                $budget->setAccount(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
}
