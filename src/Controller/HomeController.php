<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response

    {

        $mail = new Mail();
        $mail->send('florian.salducci@gmail.com','Bob','FirstMail','Hello World');

        return $this->render('home/index.html.twig');
    }
}
