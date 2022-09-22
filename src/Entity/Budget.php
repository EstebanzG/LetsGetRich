<?php

namespace App\Entity;

use App\Repository\BudgetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
class Budget
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Category = null;

    #[ORM\Column]
    private ?float $Amount = null;

    #[ORM\ManyToOne(inversedBy: 'Budgets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $Account = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\OneToMany(mappedBy: 'Budget', targetEntity: Expense::class)]
    private Collection $Expenses;

    #[ORM\Column]
    private ?float $LimitAmount = null;

    public function __construct()
    {
        $this->Expenses = new ArrayCollection();
        $this->CreatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?string
    {
        return $this->Category;
    }

    public function setCategory(string $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    public function getAmount($date = null): ?float
    {
        if($date != null){
            $expenses = $this->getExpenses($date);
            $amount = 0;
            foreach ($expenses as $expense){
                $amount += $expense->getAmount();
            }
            return $amount;
        }
        return $this->Amount;
    }

    public function setAmount(float $Amount): self
    {
        $this->Amount = $Amount;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->Account;
    }

    public function setAccount(?Account $Account): self
    {
        $this->Account = $Account;

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

    public function getExpenses($date = null): array
    {
        if($date != null){
            $expenses = $this->getExpenses();
            $toReturn = [];
            foreach ($expenses as $expense) {
                $toReturn[] = $expense;
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
        return $this->Expenses->toArray();
    }

    public function getExpensesSince($date):array
    {
        $expenses = $this->getExpenses();
        $toReturn = [];
        foreach ($expenses as $expense) {
            $toReturn[] = $expense;
        }
        $toReturn = array_filter($toReturn,
            function($expense) use ($date) {
                return $expense->getDate() > $date;
            });

        return $toReturn;
    }

    public function getBalanceSince($date):int
    {
        $expenses = $this->getExpensesSince($date);
        $toReturn = 0;
        foreach ($expenses as $expense){
            $toReturn += $expense->getAmount();
        }
        return $toReturn;
    }

    public function addExpense(Expense $expense): self
    {
        if (!$this->Expenses->contains($expense)) {
            $this->Expenses->add($expense);
            $expense->setBudget($this);
        }

        return $this;
    }

    public function removeExpense(Expense $expense): self
    {
        if ($this->Expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getBudget() === $this) {
                $expense->setBudget(null);
            }
        }

        return $this;
    }

    public function getLimitAmount(): ?float
    {
        return $this->LimitAmount;
    }

    public function setLimitAmount(float $LimitAmount): self
    {
        $this->LimitAmount = $LimitAmount;

        return $this;
    }
}
