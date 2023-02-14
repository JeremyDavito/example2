<?php

namespace App\Controller\Backoffice;


use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/api/v1/product', name: 'app_api_product')]
class ProductControllerAPI extends AbstractController
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
        /* v1/categories?limit=50" */
       
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
