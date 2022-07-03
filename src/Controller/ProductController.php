<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\SearchType;
use App\Entity\Product;
use App\Classe\Search;



class ProductController extends AbstractController
{
    private $em;

public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/nos-produits', name: 'products')]
    public function index(Request $request): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class , $search);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $products = $this->em->getRepository(Product::class)->findWithSearch($search);
            //dd($search);
        } else {
            $products = $this->em->getRepository(Product::class)->findAll();
        }

        return $this->render('product/index.html.twig',[
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/produit/{slug}', name: 'product')]
    public function show($slug): Response
    {

        $product = $this->em->getRepository(Product::class)->findOneBySlug($slug);
        $products = $this->em->getRepository(Product::class)->findByIsBest(1);

        if(!$product){
            return $this->redirectToRoute('Products');
        }
        return $this->render('product/show.html.twig',[
            'product' => $product,
            'products' => $products,

        ]);
    }
}
