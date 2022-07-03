<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RegisterType;
use App\Entity\User;


class RegisterController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/inscription', name: 'register')]
    public function index(Request $request, EntityManagerInterface $em,UserPasswordEncoderInterface $encoder): Response
    {
        $notification = null;

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $search_email = $em->getRepository(User::class)->findOneByEmail($user->getEmail());

            if(!$search_email){
                $password = $encoder->encodePassword($user,$user->getPassword());
                $user->setPassword($password);
                $em->persist($user);
                $em->flush();

                $mail = new Mail();
                $content = "Bonjour ".$user->getFirstname()."<br> Bienvue sur le Sinex Store la boutique 100% Geek";
                $mail->send($user->getEmail(), $user->getFirstname(),'Bienvenue sur le Sinex Store ', $content);
                $notification ="Votre compte a bien été créé , vous pouvez vous connecter";

            }else{
                $notification = "L'adresse email est déjà utilisée";
            }

            return $this->redirectToRoute('home');
        }


        return $this->render('register/registerForm.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
        ]);
    }
}
