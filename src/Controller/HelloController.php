<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * @Route("/hello/{prenom?world}", name="hello", methods={"GET","POST"})
     */
    public function hello(Request $request,$prenom): Response
    {
        return $this->render('hello/index.html.twig', [
            'controller_name' => 'HelloController',
            'prenom' => $prenom
        ]);
    }
}
