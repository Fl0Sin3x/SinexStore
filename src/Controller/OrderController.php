<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use App\Entity\Order;
use App\Classe\Cart;
use Stripe\Stripe;

class OrderController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/commande', name: 'order')]
    public function index(Cart $cart, Request $request): Response
    {
        //Permet de rediriger le user ci celui  n'a pas de adresse
        if (!$this->getUser()->getAdresses()->getValues()) {
            return $this->redirectToRoute('account_adress_add');
        }

        // Form for choice tra, livraison, etc.
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'order_recap', methods: ['POST'])]
    public function add(Cart $cart, Request $request): Response
    {

        // Form for choice tra, livraison, etc.
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime();
            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('adresses')->getData();
            $delivery_content = $delivery->getFirstName() . ' ' . $delivery->getLastName();


            if ($delivery->getAdress()) {
                $delivery_content .= '<br/>' . $delivery->getCompany();
            }
            $delivery_content .= '<br/>' . $delivery->getPhone();
            $delivery_content .= '<br/>' . $delivery->getAdress();
            $delivery_content .= '<br/>' . $delivery->getPostal() . ' ' . $delivery->getCity();
            $delivery_content .= '<br/>' . $delivery->getCountry();

            //dd($delivery_content);
            // Register my order Order()
            $order = new Order();
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);

            $this->em->persist($order);


            foreach ($cart->getFull() as $product) {

                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->SetTotal($product['product']->getPrice() * $product['quantity']);
                $this->em->persist($orderDetails);



            }
            //dd($order);
                $this->em->flush();

            return $this->render('order/add.html.twig', [
                'cart' => $cart->getFull(),
                'carrier' => $carriers,
                'delivery' => $delivery_content,
                'reference' => $order->getReference()
            ]);
        }
        return $this->redirectToRoute('cart');
    }

}
