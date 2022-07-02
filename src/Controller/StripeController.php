<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Dotenv\Dotenv;
use Stripe\Checkout\Session;
use App\Entity\Product;
use App\Entity\Order;
use App\Classe\Cart;
use Stripe\Stripe;



class StripeController extends AbstractController
{
  #[Route('/commande/create-session/{reference}', name: 'stripe_create_session', methods: 'GET')]
    public function index($reference, EntityManagerInterface $em)
    {
        $productsForStripe = [];
        $YOUR_DOMAINE ='http://127.0.0.1:8000/';

        $order = $em->getRepository(Order::class)->findOneByReference($reference);

      if(!$order){
          return new JsonResponse(['error' => 'order']);
      }

        foreach ($order->getOrderDetails()->getValues([]) as $product) {

            $productsForStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' =>[sprintf('%s/uploads/%s',  $YOUR_DOMAINE, $em->getRepository(Product::class)->findOneByName($product->getProduct())->getIllustration())],
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];



        }


        $productsForStripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => $order->getCarrierName()
                ],
            ],
            'quantity' => 1,
        ];

     Stripe::setApiKey('sk_test_51LFdYKLYsUcdCTqMYeGh8LTUOfhr3uPN8bQpUlflbP6CRbjoX8ypzP0S878gQKdZv76oug4C8WeNjqXnrL2kliOV00H0yMjwsf');

        $checkout_session = Session::create([
            'customer_email' =>$this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [$productsForStripe],
            'mode' => 'payment',
            'success_url' =>  $YOUR_DOMAINE . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' =>  $YOUR_DOMAINE . '/commande/erreur/{CHECKOUT_SESSION_ID}',

        ]);


        $order->setStripeSessionID($checkout_session->url);
        $em->flush();

        return new JsonResponse(['id' => $checkout_session->url]);
    }
}
