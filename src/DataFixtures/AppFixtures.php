<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Comments;
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
            $product->setDescription('description');
            $product->setImgBackground('urld151502020.jpg');
            $product->setGroupe('tricks');
            $product->setDateCreation(\DateTime::createFromFormat('Y-m-d H:i:s','2021-12-15 10:28:36'));

            $manager->persist($product);
        }

        for ($j = 0; $j < 20; $j++) {

            $product = new Comments();
            $product->setAuthor('trick ' . $j);
            $product->setMessage($this->RandomString());
            $product->setCreatedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2021-12-15 10:28:36'));
            $product->setIdTrick(rand(24, 28));

            $manager->persist($product);
        }

        $manager->flush();
    }

    function RandomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand = '';
        for ($i = 0; $i < 10; $i++) {
            $randstring = $characters[rand(0, strlen($characters)-1)];
            $rand .= $randstring;
        }
        return $rand;
    }
}