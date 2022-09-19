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

    public function getAmount(): ?float
    {
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

    /**
     * @return Collection<int, Expense>
     */
    public function getExpenses(): Collection
    {
        return $this->Expenses;
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
