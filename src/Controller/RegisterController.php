<?php

namespace App\Controller;

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
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $password = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('home/index.html.twig');
        }


        return $this->render('register/registerForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
