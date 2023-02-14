<?php

namespace App\Controller\Api;


use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/api/v1/product', name: 'app_product')]
class ProductController extends AbstractController
{

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    
    #[Route('/', name: '_browse',  methods: [ 'GET' ]/* , requirements: ['id =\d+'] */ )]
    public function index( Request $request, ProductRepository $productRepository ): Response
    {
       
        $limit = $request->query->get('limit');
        $productId = $request->query->get('product');
       
        return $this->json(
            $productRepository->findByActive($limit),
            200,
            [],
             [
                'groups' => ['show_product']
            ] 
        );


    }





}
