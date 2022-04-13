<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Tricks;
use App\Entity\Users;
use App\Form\CommentType;
use App\Form\Comment0Type;
use App\Form\UserUpdateType;
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
        $user = $this->getUser();

        return $this->render('index/index.html.twig', [
            'tricks' => $tricks ?? null,
            'user' => $user ?? null,
        ]);
    }

    /**
     * @Route("/profil/{id}", name="profil", methods={"GET"})
     */
    public function profil($id)
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
    public function test($id)
    {
        $trick = $this->getDoctrine()->getManager()->getRepository(Tricks::class)->find($id);
        $comments = $trick->getComments()->getValues();
        $medias = $trick->getMedias()->getValues();
        $user = $this->getUser();

        return $this->render(
            'index/test.html.twig',
            [
                'trick' => $trick,
                'comments' => $comments,
                'medias' => $medias,
                'user' => $user
            ]
        );
    }

    /**
     * @Route("/forum", name="forum", methods={"GET"})
     */
    public function forum()
    {
        $comments = $this->getDoctrine()->getManager()->getRepository(Comments::class)->findBy(
            ['trick_id' => 1],
            ['created_at' => 'DESC']
        );

        $user = $this->getUser();

        return $this->render(
            'index/forum.html.twig',
            [
                'comments' => $comments,
                'user' => $user
            ]
        );
    }

    /**
     * @Route("/forum/comment/add", name="newComment", methods={"GET","POST"})
     */
    public function newComment(Request $request)
    {
        if ($this->getUser() === null) {
            return $this->render('403.html.twig');
        }

        $user_id = $this->getUser()->getId();

        $comment = new Comments();

        $trick = $this->getDoctrine()->getManager()->getRepository(Tricks::class)->find(1);
        $form = $this->createForm(Comment0Type::class, ['trick'=> 1, 'user_id' => $user_id]);

        $form->handleRequest($request);

        //soumission du form
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setMessage(htmlspecialchars($form->get('message')->getData()));
            $comment->setIdTrick($form->get('trick_id')->getData());
            $comment->setTrick($trick);
            $comment->setUser($this->getUser());
            $comment->setAuthor($this->getUser()->getEmail());
            $comment->setCreatedAt(new \DateTimeImmutable('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('forum');
        }

//        if ($trick !=) {
//            return $this->render('404.html.twig');
//        }

        return $this->render('comments/commentForm.html.twig', [
            'user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }




    /**
     * @Route("/comment/delete/{id}", name="deleteComment", methods={"GET"})
     */
    public function deleteComment($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comment = $entityManager->getRepository(Comments::class)->find($id);

        if (!$comment) {
            return $this->render('404.html.twig');
        }

        $entityManager->remove($comment);
        $entityManager->flush();

        $comments = $entityManager->getRepository(Comments::class)->findAll();

        $this->addFlash('success', 'Le commentaire a bien été supprimé.');

        return $this->redirectToRoute('forum', [
            'comments' => $comments,
            'user' => $this->getUser(),
        ]);
    }
}
