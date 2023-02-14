<?php

namespace App\Controller\Backoffice;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/backoffice', name: 'app_backoffice')]
class HomeController extends AbstractController
{
   

    #[Route('/home', name: '_home')]
    public function index(): Response
    {
        return $this->render('backoffice/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }



}
