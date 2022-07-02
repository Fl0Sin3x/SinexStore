<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;



class AccountOrderController extends AbstractController {


  private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    #[Route('/compte/mes-commandes', name: 'account_order')]
    public function index(): Response
    {
        $orders = $this->em->getRepository(Order::class)->findSuccessOrders($this->getUser());


        return $this->render('account/order.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/compte/mes-commandes/{reference}', name: 'account_order_show')]
    public function show($reference): Response
    {
        $order = $this->em->getRepository(Order::class)->findOneByReference($reference);

        if(!$order || $order->getUser() !== $this->getUser()) {
            $this->addFlash('danger', 'Cette commande n\'existe pas');
            return $this->redirectToRoute('account_order');
        }


        return $this->render('account/order_show.html.twig', [
            'order' => $order,
        ]);
    }
}
