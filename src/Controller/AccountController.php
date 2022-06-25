<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/compte')]
class AccountController extends AbstractController
{
    #[Route('/mon_compte', name: 'my-account')]
    public function index(): Response
    {

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController'
        ]);
    }
}
