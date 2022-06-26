<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ChangePasswordType;


class AccountPasswordController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/compte/modifier-mon-mot-de-passe', name: 'account_password')]
    public function index(Request $request, UserPasswordEncoderInterface $encoder,EntityManagerInterface $em): Response
    {
        $notification = null;

        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData();

          if ($encoder->isPasswordValid($user, $old_pwd)){

              $new_pwd = $form->get('new_password')->getData();
              $password = $encoder->encodePassword($user, $new_pwd);

              $user->setPassword($password);
                $em->persist($user);
              $em->flush();
              $notification = "Votre mot de passe à bien été mis a jour";

          } else {
              $notification = "Mot de passe de passe actuel est incorrect";
          }
        }

        return $this->render('account/password.html.twig',[
            'form' => $form->createView(),
            'notification' => $notification

        ]);
    }
}
