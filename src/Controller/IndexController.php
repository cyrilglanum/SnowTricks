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
            'tricks' => $tricks ?? null,
            'user' => $this->getUser() ?? null,
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

    /**
     * @Route("/testing/{id}", name="testing", methods={"GET"})
     */
    public function test(Request $request, $id)
    {
        $trick = $this->getDoctrine()->getManager()->getRepository(Tricks::class)->find($id);
        $comments = $trick->getComments()->getValues();
        $medias = $trick->getMedias()->getValues();
        $user = $this->getUser();

        return $this->render('index/test.html.twig',
            [
            'trick' => $trick,
            'comments' => $comments,
            'medias' => $medias,
            'user' => $user
            ]
        );
    }
}
