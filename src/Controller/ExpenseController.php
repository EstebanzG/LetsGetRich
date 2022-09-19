<?php

namespace App\Controller;

use App\Repository\AccountRepository;
use App\Repository\BudgetRepository;
use App\Repository\ExpenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ExpenseController extends AbstractController
{
    #[Route('/expense', name: 'app_expense')]
    public function index(EntityManagerInterface $ma, ExpenseRepository $manager, BudgetRepository $managerBud, AccountRepository $managerAcc): Response
    {
        return $this->render('expense/index.html.twig',[$this]);
    }
}
