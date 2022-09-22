<?php

namespace App\Controller;

use App\Entity\Budget;
use App\Entity\Expense;
use App\Entity\Account;
use App\Form\AccountFormType;
use App\Form\NewExpenseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/compte/ajouter', name: 'app_account_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $account = new Account();
        $form = $this->createForm(AccountFormType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $account->setUser($this->getUser());
            $entityManager->persist($account);

            $budget = new Budget();
            $budget->setAmount($account->getBalance())
                ->setLimitAmount(0)
                ->setAccount($account)
                ->setCategory("Divers");

            $entityManager->persist($budget);

            $entityManager->flush();
            $this->addFlash(
                'success',
                'Félicitation, le compte a bien été ajouté.'
            );
        }

        return $this->render('account/add.html.twig', [
            'controller_name' => 'AccountController',
            'newAccount' => $form->createView(),
        ]);
    }

    #[Route('/compte/{id}', name: 'app_account')]
    #[Security("is_granted('ROLE_USER') and user === account.getUser()")]
    public function index(Request $request, EntityManagerInterface $entityManager, SessionInterface $session, Account $account): Response
    {
        $expense = new Expense();
        $budgets = [];
        foreach ($account->getBudgets() as $budg){
            $budgets[] = $budg;
        }
        $form = $this->createForm(NewExpenseType::class, $expense, array('budgets' => $budgets));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($form->get('Movement')->getData() == 'expense'){
                if($expense->getAmount() > 0){
                    $expense->setAmount($expense->getAmount() * -1);
                }
            } else {
                if($expense->getAmount() < 0){
                    $expense->setAmount($expense->getAmount() * -1);
                }
            }


            $entityManager->persist($expense);
            $entityManager->flush();
        }


        $date = $session->get('date_account', false);
        if(!$date){
            $date = new \DateTime("now");
            $session->set('date_account', $date);
        }


        $month = $session->get('changeMonth');
        $session->set('changeMonth', 'other');
        if($month == "next") $date->modify('+1 month');
        else if($month == "back") $date->modify('-1 month');

        return $this->render('account/index.html.twig', [
            'account' => $account,
            'expenses' => $account->getExpenses($date),
            'date' => $date,
            'newExpense' => $form->createView()
        ]);
    }

    #[Route('/compte/supprimer/{id}', name: 'app_account_delete')]
    public function delete(SessionInterface $session, EntityManagerInterface $manager, Account $account): Response
    {
        foreach ($account->getBudgets() as $budgs){
            foreach ($budgs->getExpenses() as $exps){
                $manager->remove($exps);
            }
            $manager->remove($budgs);
        }
        $manager->remove($account);
        $manager->flush();
        return $this->redirectToRoute('app_home');
    }

    #[Route('/compte/{id}/{month}', name: 'app_account_month')]
    #[Security("is_granted('ROLE_USER') and user === account.getUser()")]
    public function changeMonth(SessionInterface $session, Account $account, $month): Response
    {
        $session->set('changeMonth', $month);
        return $this->redirectToRoute('app_account', ['id' => $account->getId()]);
    }

    #[Route('/compte/modifier/{id}', name: 'app_account_modify')]
    #[Security("is_granted('ROLE_USER') and user === account.getUser()")]
    public function modify(Account $account, $date=null): Response
    {
        return $this->render('account/modify.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

}
