<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Tricks;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 3; $i++) {
            $product = new Tricks();
            $product->setName('trick '.$i);
            $product->setName('trick '.$i);
            $product->setDescription('description');
            $product->setImgBackground('urld151502020.jpg');
            $product->setGroupe('tricks');
//            $product->setDateCreation(Date('Y-m-d H:i:s', ));
//            $product->setDateModification(\DateTime::createFromFormat('Y-m-d H:i:s'));

            $manager->persist($product);
        }

        $manager->flush();
    }
}