<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Expense;
use App\Repository\AccountRepository;
use App\Repository\BudgetRepository;
use App\Repository\ExpenseRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ExpenseController extends AbstractController
{
    #[Route('/expense', name: 'app_expense')]
    public function index(EntityManagerInterface $ma, ExpenseRepository $manager, BudgetRepository $managerBud, AccountRepository $managerAcc): Response
    {
        return $this->render('expense/index.html.twig',);
    }

    #[Route('/depense/supprimer/{id}', name: 'app_expense_delete')]
    public function delete(Expense $expense, SessionInterface $session, EntityManagerInterface $manager): Response
    {
        $manager->remove($expense);
        $manager->flush();
        return $this->redirectToRoute('app_account', ['id' => $expense->getBudget()->getAccount()->getId()]);
    }
}
