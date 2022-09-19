<?php

namespace App\Controller;

use App\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/compte/ajouter', name: 'app_account_add')]
    public function ajouter(): Response
    {
        return $this->render('account/add.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    #[Route('/compte/{id}', name: 'app_account')]
    #[Security("is_granted('ROLE_USER') and user === account.getUser()")]
    public function index(Account $account): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

}
