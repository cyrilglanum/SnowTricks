<?php


namespace App\Controller;



use App\Entity\Tricks;
use App\Form\TrickType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @Route("/form/new")
     */
    public function new(Request $request)
    {
        $trick = new Tricks();
        $trick->setName('Hello World');
        $trick->setImgBackground('enbfbsbfbe262511.jpg');
        $trick->setDescription('Ce trick reprend les bases du snowboard ...');
        $trick->setDateCreation(new \DateTime('now'));


        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($trick);
        $em->flush();

    }

        return $this->render('tricks/newTrick.html.twig', array(
            'form' => $form->createView(),
            'message' => "le trick a bien été ajouté"
        ));
    }


    /**
     * @Route("/tricks")
     */
    public function tricks(Request $request)
    {
        $trick = new Tricks();
        $trick->setName('Hello World');
        $trick->setImgBackground('enbfbsbfbe262511.jpg');
        $trick->setDescription('Ce trick reprend les bases du snowboard ...');

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        dump($trick);
    }

        return $this->render('tricks/newTrick.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}