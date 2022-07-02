<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;
use App\Classe\Cart;



class OrderValidateController extends AbstractController
{
    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    #[Route('/commande/merci/{stripeSessionId}', name: 'order_success' ,methods: 'GET')]
    public function index($stripeSessionId, Cart $cart)
    {
        $order = $this->em->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        if ($order->getState() === 0) {
            $order->setState(1);
            $this->em->flush();
            $cart->remove();

            // Send email to user
        }


        return $this->render('order_success/validate.html.twig');
    }
}
