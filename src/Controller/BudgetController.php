<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Budget;
use App\Form\BudgetFormType;
use App\Form\ChooseBudgetDateType;
use App\Form\ModifyBudgetFormType;
use App\Form\ModifyBudgetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class BudgetController extends AbstractController
{
    #[Route('/budget/{id}', name: 'app_budget')]
    public function index(Request $request, SessionInterface $session, EntityManagerInterface $entityManager, Budget $budget): Response
    {
        $formDate = $this->createForm(ChooseBudgetDateType::class);
        $formDate->handleRequest($request);
        if ($formDate->isSubmitted() && $formDate->isValid()) {
            $session->set('date_budget', $formDate->get('Date')->getData());
            return $this->redirectToRoute('app_budget', ['id' => $budget->getId()]);
        }

        $date = $session->get('date_budget', false);
        if(!$date){
            $date = new \DateTime("now");
            $date->modify('-1 month');
        }

        $formModify = $this->createForm(ModifyBudgetFormType::class, $budget);
        $formModify->handleRequest($request);
        if($formModify->isSubmitted() && $formModify->isValid()){
            $entityManager->persist($budget);
            $entityManager->flush();
            return $this->redirectToRoute('app_budget', ['id' => $budget->getId()]);
        }

        return $this->render('budget/index.html.twig', [
            'budget' => $budget,
            'date' => $date,
            'formDate' => $formDate->createView(),
            'formModify' => $formModify->createView()
        ]);
    }

    #[Route('/budget/ajouter/{id}', name: 'app_budget_add')]
    public function add(Request $request, EntityManagerInterface $entityManager, Account $account): Response
    {
        $budget = new Budget();
        $form = $this->createForm(BudgetFormType::class, $budget);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $budget->setAmount(0);
            $budget->setAccount($account);
            $entityManager->persist($budget);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Félicitation, le budget a bien été créé.'
            );
        }

        return $this->render('budget/add.html.twig', [
            'controller_name' => 'BudgetController',
            'newBudget' => $form->createView(),
        ]);
    }
}
