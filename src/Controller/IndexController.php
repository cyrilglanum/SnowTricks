<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Entity\Users;
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
    public function profil(Request $request, $id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository(Users::class)->find($id);

        if (!$user) {
            return $this->render('404.html.twig');
        }
        return $this->render('profil/profil.html.twig', array('user' => $user));
    }

    /**
     * @Route("/profil/edit/{id}", name="editProfil", methods={"GET"})
     */
    public function editProfil(Request $request, $id)
    {
        dd('edit profil');
//        $trick = new Tricks();
//        $form = $this->createForm(TrickType::class, $trick);
//
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $trick->setName($form->getName());
//            $brochureFile = $form->get('img_background')->getData();
//            if ($brochureFile) {
//                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
//                // this is needed to safely include the file name as part of the URL
//                $safeFilename = $slugger->slug($originalFilename);
//                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
//                $trick->setImgBackground($newFilename);
//
//                // Move the file to the directory where brochures are stored
//                try {
//                    if (str_contains('ocprojects.fr', $_SERVER['HTTP_HOST'])) {
//                        $brochureFile->move(
//                            $this->getParameter('prodTrickFiles'),
//                            $newFilename
//                        );
//                    } else {
//                        $brochureFile->move(
//                            $this->getParameter('trickFiles'),
//                            $newFilename
//                        );
//                    }
//                } catch (FileException $e) {
//                    return $e;
//                }
//            }
//
//            $trick->setDescription($form->get('description')->getData());
//            $trick->setDateCreation(new \DateTime('now'));
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($trick);
//            $em->flush();
//
//            return $this->render('profil/profil.html.twig', array());
//        }
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
