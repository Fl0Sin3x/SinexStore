<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\OrderType;
use App\Classe\Cart;

class OrderController extends AbstractController
{
    #[Route('/commande', name: 'order')]
    public function index(Cart $cart, Request $request): Response
    {
        //Permet de rediriger le user ci celui  n'a pas de adresse
        if(!$this->getUser()->getAdresses()->getValues()){
            return $this->redirectToRoute('account_adress_add');
        }

        // Form for choice tra, livraison, etc.
        $form = $this->createForm(OrderType::class,null ,[
            'user' => $this->getUser(),
        ]);

        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {


        }

        return $this->render('order/index.html.twig' , [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }
}
