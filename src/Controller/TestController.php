<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test/{age<\d+>?0}", name="test",methods={"GET","POST"})
     */
//    récupération de age dans la route et passage en paramètres.
    public function test(Request $request,$age): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController', ['age' => $age]
        ]);
    }
}
