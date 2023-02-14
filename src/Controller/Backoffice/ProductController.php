<?php

namespace App\Controller\Backoffice;

use App\Entity\Brand;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\DBAL\Types\DateTimeTzType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


#[Route('/backoffice/product', name: 'app_product')]
class ProductController extends AbstractController
{

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

   
    #[Route('/', name: '_browse')]
    public function browse(ProductRepository $productRepository, ManagerRegistry $doctrine, PaginatorInterface $paginator, Request $request): Response
    {
        $products = $paginator->paginate(
            $productRepository->findBy([], ['created_at' => 'DESC']), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('backoffice/product/browse.html.twig', [
           /*  'categoryProducts' => $categoryProducts, */
            'products' => $products
        ]);
    }



    /**
     * @Route("/add", name="_add")
     */
    public function add(Request $request,  ManagerRegistry $doctrine): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        $product->setPicture('PictureHOLDER');
        $product->setRate(0);
        $product->setStatus('En attente');
        $product->setCreatedAt(new \DateTimeImmutable('now'));
        $product->setUpdatedAt(new \DateTimeImmutable('now'));

        if ($form->isSubmitted() && $form->isValid()) {

            $catId = $form->get("category")->getData(); 
            $cat = $doctrine->getRepository(Category::class)
               ->findOneBy(['id' => $catId]);
               $product->addCategory($cat); 
            
           

            $this->manager->persist($product);
            $this->manager->flush();

            $this->addFlash('success', 'Le produit a bien été ajoutée.');

            return $this->redirectToRoute('app_product_browse', ['id' => $product->getId()]);
        }

        return $this->render('backoffice/product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /** 
     * @Route("/edit/{id}", name="_edit")
     */
    public function edit(Request $request, ManagerRegistry $doctrine, int $id): Response
    {

        $product = $doctrine->getRepository(Product::class)->find($id);
        $productId = $product->getId();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        $product->setPicture('PictureHOLDER');
        $product->setRate(0);
        $product->setStatus('En attente');
        /* $product->setCreatedAt(New \DateTimeImmutable('now')); */
        $product->setUpdatedAt(new \DateTimeImmutable('now'));
        if ($form->isSubmitted() && $form->isValid()) {

            
            $catId = $form->get("category")->getData(); 
            $cat = $doctrine->getRepository(Category::class)
               ->findOneBy(['id' => $catId]);
               $product->addCategory($cat);   
               // FOR THE MOMENT CAN ONLY ADD AND CREATE A NEW CATEGORY PRODUCT, CANNOT JUST SET TO A NEW ONE

            $this->manager->persist($product);
            $this->manager->flush();

            $this->addFlash('success', 'Le produit a bien été ajoutée.');

            return $this->redirectToRoute('app_backoffice_home', ['id' => $product->getId()]);
        }

        return $this->render('backoffice/product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: '_read')]
    public function read(Product $product, ManagerRegistry $doctrine, int $id): Response
    {
        $productCat = $doctrine->getRepository(Product::class)->find($id);


            $categoryProducts = $doctrine->getRepository(Product::class)->find($id);
            $cat = $categoryProducts->getCategory();
         

        
        return $this->render('backoffice/product/read.html.twig', [
           'category' =>  $cat,	
            'product' => $product,
        ]);
    }


    #[Route('/{id}/delete', name: '_delete')]
    public function delete(EntityManagerInterface $manager, Product $product, Request $request): Response
    {
        $submittedCsrfToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete_product_' . $product->getId(), $submittedCsrfToken)) {
            $manager->remove($product);
            $manager->flush();

            $this->addFlash('success', 'Product has been deleted.');

            return $this->redirectToRoute('app_product_browse');
        }

        $this->addFlash('danger', 'The product has not been deleted.');

        return $this->redirectToRoute('app_product_browse');
    }
}
