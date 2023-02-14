<?php

namespace App\Controller\Backoffice;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice/user', name: 'app_user')]
class UserController extends AbstractController
{

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    #[Route('/', name: '_browse')]
    public function browse(UserRepository $userRepository, PaginatorInterface $paginator, HttpFoundationRequest $request): Response
    {
        $users = $paginator->paginate(
            $userRepository->findAll(),
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('backoffice/users/browse.html.twig', [
            'users' => $users
        ]);
    }

     /**
     * @Route("/{id}", name="_read")
     */
    public function read(User $user): Response
    {
        return $this->render('backoffice/users/read.html.twig', [
            'user' => $user,
        ]);
    }





}
