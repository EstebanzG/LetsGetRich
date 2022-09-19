<?php

namespace App\DataFixtures;

use App\Repository\ExpenseRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Account;
use App\Entity\Budget;
use App\Entity\Expense;
use Faker\Generator;
use Faker\Factory;


class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {


        //Account
        $accounts = [];
        for($i = 0; $i < 1; $i++) {
            $account = new Account();
            $account->setName($this->faker->word())
                ->setBank($this->faker->word())
                ->setBalance(0);
            $accounts[] = $account;
            $manager->persist($account);
        }

        //Budget
        $budgets = [];
        for($i = 0; $i < 2; $i++){
            $budget = new Budget();
            $budget->setAccount($accounts[0])
                ->setAmount(0)
                ->setCategory($this->faker->word())
                ->setLimitAmount(mt_rand(50,1000));
            $budgets[] = $budget;
            $manager->persist($budget);
        }

        //Expenses
        for($i = 0; $i < 20; $i++){
            $expense = new Expense();
            $e = new Expense();
            $expense->setLocation($this->faker->word())
                ->setAmount(mt_rand(10,30))
                ->setBudget($budgets[mt_rand(0,1)])
                ->setDetail($this->faker->sentence(mt_rand(0,10)))
                ->setDate($this->faker->dateTime());
            $manager->persist($expense);
        }

        $manager->flush();
    }
}
