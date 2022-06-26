<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\AdressType;
use App\Entity\Adress;


class AccountAdressController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/compte/adresses', name: 'account_adress')]
    public function index(): Response
    {

        return $this->render('account/adress.html.twig',);
    }

    #[Route('/compte/ajouter-une-adresse', name: 'account_adress_add')]
    public function add(Request $request): Response
    {
        $adress = new Adress();

        $form = $this->createForm(AdressType::class , $adress);

        $form->handleRequest($request);
        if(($form->isSubmitted() && $form->isValid())){
            $adress->setUser($this->getUser());
            $this->em->persist($adress);
            $this->em->flush();
            return $this->redirectToRoute('account_adress');
        }

        return $this->render('account/adress_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/compte/modifier-une-adresse/{id}', name: 'account_adress_edit')]
    public function edit(Request $request , $id): Response
    {
        $adress = $this->em->getRepository(Adress::class)->findOneById($id);

        if(!$adress || $adress->getUser() !== $this->getUser()){
            return $this->redirectToRoute('account_adress');
        }

        $form = $this->createForm(AdressType::class , $adress);

        $form->handleRequest($request);
        if(($form->isSubmitted() && $form->isValid())){
            $this->em->flush();
            return $this->redirectToRoute('account_adress');
        }

        return $this->render('account/adress_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/compte/supprimer-une-adresse/{id}', name: 'account_adress_delete')]
    public function delete( $id): Response
    {
        $adress = $this->em->getRepository(Adress::class)->findOneById($id);

        if(!$adress || $adress->getUser() == $this->getUser()){
            $this->em->remove($adress);
            $this->em->flush();
        }

      return $this->redirectToRoute('account_adress');
    }

}
