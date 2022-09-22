<?php

namespace App\Controller;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/', name: 'app_home')]
    public function index(AccountRepository $accountRepository, SessionInterface $session,): Response
    {
        $date = $session->get('date_home');
        if(!$date){
            $date = new \DateTime("now");
            $session->set('date_home', $date);
        }

        $month = $session->get('changeMonth');
        $session->set('changeMonth', 'other');
        if($month == "next") $date->modify('+1 month');
        else if($month == "back") $date->modify('-1 month');

        return $this->render('home/index.html.twig', [
            'accounts' => $accountRepository->findBy(['User' => $this->getUser()]),
            'date' => $date,
        ]);
    }

    #[Route('/home/{month}', name: 'app_home_month')]
    #[Security("is_granted('ROLE_USER')")]
    public function changeMonth(SessionInterface $session, AccountRepository $accountRepository, $month): Response
    {
        $session->set('changeMonth', $month);
        return $this->redirectToRoute('app_home', [
            'accounts' => $accountRepository->findBy(['User' => $this->getUser()]),
        ]);
    }
}
