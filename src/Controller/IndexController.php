<?php

namespace App\Controller;

use App\Entity\Tricks;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        $tricks = $this->getDoctrine()->getRepository(Tricks::class)->findAll();

        return $this->render('index/index.html.twig', [
            'controller_name' => 'BlogController',
            'tricks' => $tricks,
            'user' => $this->getUser(),
        ]);
    }
}
