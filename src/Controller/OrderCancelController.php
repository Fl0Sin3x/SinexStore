<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;


class OrderCancelController extends AbstractController
{

    private $em;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

  #[Route('/commande/erreur', name: 'order_cancel', methods: 'GET')]
    public function index($stripeSessionId)
    {
        $order = $this->em->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        return $this->render('order_cancel/index.html.twig',[
            'order' => $order
        ]);
    }
}
