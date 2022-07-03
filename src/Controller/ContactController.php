<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

 #[Route('/nous-contacter', name: 'contact')]
    public function index(Request $request)
    {

        $form = $this->createForm(ContactType::class);
        $user = $this->em->getRepository(User::class)->findOneByEmail($request->get('email'));
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('notice', 'Merci de nous avoir contacté. Notre équipe va vous répondre dans les meilleurs delairs.');

            $content = "Bonjour ,<br>Merci de nous avoir contacté.<br>";;
            $mail = new Mail();
            $mail -> send('flocondingtest@gmail.com', 'Sinex Store', 'Vous nous avez contacter', $content);
        }

            return $this->render('contact/index.html.twig', ['form' => $form->createView()
            ]);



    }
}
