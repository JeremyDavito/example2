<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Brand;
use App\Entity\User;
use App\Entity\Address;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

         
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setPassword('admin');
        $user->setFirstName('admin2');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setLastName('admin2');
        $user->setIsVerified(1);

        $address = new Address();
        $address->setNumber('73');
        $address->setStreet('Cours Jean Jaures');
        $address->setCountryCode('example.com');
        $address->setCity('Grenoble');
        $address->setAppartmentNumber('5');
        $address->setMessage('example.com');
        $address->setZipCode('38000');

        $user->addAddress($address);

        $manager->persist($address);
        $manager->persist($user);
       



        for ($index = 1; $index <= 10; $index++) {
            $category = new Category();
            $category->setName('catégorie ' . $index);
            $category->setPicture('category_placeholder.jpg');
            $category->setSubtitle('category_placeholder.jpg');
            $manager->persist($category);
        } 

        $brand = new Brand();
        $brand->setName('Brand');
        $brand->setCreatedAt(New \DateTimeImmutable('now'));
        $brand->setUpdatedAt(New \DateTimeImmutable('now'));

        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(199);
        $product->setDescription('Ergonomic and stylish!');
        $product->setPicture('PictureHOLDER');
        $product->setRate(7);
        $product->setStatus('Available');
        $product->setCreatedAt(New \DateTimeImmutable('now'));
        $product->setUpdatedAt(New \DateTimeImmutable('now'));
        $product->setBrand($brand);
       /* 
        $cat = $doctrine->getRepository(Category::class)
            ->findOneBy(['id' => '1']);
        $product->addCategory($cat);*/
        $manager->persist($product); 

        

        $manager->flush();
    }
}
