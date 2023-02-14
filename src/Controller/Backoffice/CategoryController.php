<?php

namespace App\Controller\Backoffice;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/backoffice/category', name: 'app_category')]
class CategoryController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    #[Route('/', name: '_browse')]
    public function browse(CategoryRepository $categoryRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $paginator->paginate(
            $categoryRepository->findBy([], ['name' => 'DESC']), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            8 /*limit per page*/
        );

        return $this->render('backoffice/category/browse.html.twig', [
            'categories' => $categories
        ]);
    }


    
    #[Route('/add', name: '_add')]
    public function add(Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        $category->setPicture('TEMPORARYPICTUREHOLDER');

        

    
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($category);
            $this->manager->flush();
            $this->addFlash('success', 'Category been had successfully.');
            return $this->redirectToRoute('app_category_browse', ['id' => $category->getId()]);
        }

        return $this->render('backoffice/category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: '_read')]
    public function read(Category $category, ManagerRegistry $doctrine, int $id): Response
    {
       
        $categoryProducts = $doctrine->getRepository(Category::class)->find($id);
        $cat = $categoryProducts ->getProducts();
        

        return $this->render('backoffice/category/read.html.twig', [
            'products' => $cat,
            'category' => $category,
        ]);
    }




}
