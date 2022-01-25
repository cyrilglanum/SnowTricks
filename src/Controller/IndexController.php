<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Entity\Users;
use App\Form\UserUpdateType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
     * @Route("/profil/edit/{id}", name="editProfil", methods={"GET", "POST"})
     */
    public function editProfil(Request $request, $id, SluggerInterface $slugger)
    {

        $user = $this->getDoctrine()->getManager()->getRepository(Users::class)->find($id);

        if (!$user) {
            return $this->render('404.html.twig');
        }

        $form = $this->createForm(UserUpdateType::class, $user);
        $form->handleRequest($request);

        //soumission du form
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUsername($form->getData()->getUsername());
            $brochureFile = $form->get('image')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                $user->setImage($newFilename);

                // Move the file to the directory where brochures are stored
                try {
                        if ($this->getParameter('prodTrickFiles')) {
                            $brochureFile->move(
                                $this->getParameter('prodTrickFiles'),
                                $newFilename
                            );
                    } else {
                        $brochureFile->move(
                            $this->getParameter('trickFiles'),
                            $newFilename
                        );
                    }
                } catch (FileException $e) {
                    return $e;
                }
            }
//            $user->setDateModification(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('profil/editProfil.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),));

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
