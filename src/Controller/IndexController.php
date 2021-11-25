<?php

namespace App\Controller;

use App\Entity\Tricks;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/profil/{id}", name="profil", methods={"GET"})
     */
    public function profil(Request $request,$id)
    {
        dd('profil id', $id);

        return $this->render('tricks/newTrick.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
