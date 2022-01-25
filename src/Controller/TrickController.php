<?php


namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Tricks;
use App\Form\CommentType;
use App\Form\TrickType;
use http\Env;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
    /**
     * @Route("/trick/new", name="newTrick")
     */
    public function new(Request $request, SluggerInterface $slugger)
    {
        $trick = new Tricks();
        $form = $this->createForm(TrickType::class, $trick);
        $user = $this->getUser();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setName(htmlspecialchars($form->getData()->getName()));
            $brochureFile = $form->get('img_background')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                $trick->setImgBackground($newFilename);

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
            $trick->setDescription(htmlspecialchars($form->get('description')->getData()));
            $trick->setDateCreation(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();
            $this->addFlash('success', 'Le trick a bien été ajouté.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('tricks/newTrick.html.twig', array(
            'form' => $form->createView(),
            'trick' => $trick,
            'user' => $user,
        ));
    }

    /**
     * @Route("/trick/editForm/{trick_id}", name="editTrick")
     */
    public function updateForm(Request $request, $trick_id, SluggerInterface $slugger)
    {
        $trick = $this->getDoctrine()->getManager()->getRepository(Tricks::class)->find($trick_id);
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        //soumission du form
        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setName(htmlspecialchars($form->getData()->getName()));
            $brochureFile = $form->get('img_background')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                $trick->setImgBackground($newFilename);

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
            $trick->setDescription(htmlspecialchars($form->get('description')->getData()));
            $trick->setDateModification(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();

            $this->addFlash('success', 'Le trick a bien été modifié.');

            return $this->redirectToRoute('app_home');
        }

        if (!$trick) {
            return $this->render('404.html.twig');
        }

        return $this->render('tricks/editTrick.html.twig', array(
            'form' => $form->createView(),
            'trick' => $trick,
        ));
    }

    /**
     * @Route("/trick/{id}", name="trick", methods={"GET"})
     */
    public function trick(Request $request, $id)
    {
        $trick = $this->getDoctrine()->getManager()->getRepository(Tricks::class)->find($id);

        if (!$trick) {
            return $this->render('404.html.twig');
        }

        $comments = $trick->getComments()->getValues();
        $medias = $trick->getMedias()->getValues();
        $user = $this->getUser();

        return $this->render('tricks/trick.html.twig', ['trick' => $trick, 'comments' => $comments, 'medias' => $medias, 'user' => $user]
        );
    }

    /**
     * @Route("/trick/delete/{id}", name="deleteTrick", methods={"GET"})
     */
    public function deleteTrick($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $trick = $entityManager->getRepository(Tricks::class)->find($id);

        if (!$trick) {
            return $this->render('404.html.twig');
        }

        $entityManager->remove($trick);
        $entityManager->flush();

        $tricks = $entityManager->getRepository(Tricks::class)->findAll();

        $this->addFlash('success', 'La figure a bien été supprimée.');

        return $this->redirectToRoute('app_home', [
            'tricks' => $tricks,
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/trick/comment/{id}", name="commentTrick", methods={"GET", "POST"})
     */
    public function commentTrick(Request $request, $id)
    {
        $trick = $this->getDoctrine()->getManager()->getRepository(Tricks::class)->find($id);
        $user_id = $this->getUser()->getId();
        $comment = new Comments();

        $form = $this->createForm(CommentType::class, ['trick' => $trick, 'user_id' => $user_id]);

        $form->handleRequest($request);

        //soumission du form
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setMessage(htmlspecialchars($form->get('message')->getData()));
            $comment->setTrick($trick);
            $comment->setUser($this->getUser());
            $comment->setAuthor($this->getUser()->getEmail());
            $comment->setCreatedAt(new \DateTimeImmutable('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('trick', ['id' => $trick->getId()]);
        }

        if (!$trick) {
            return $this->render('404.html.twig');
        }

        return $this->render('comments/commentForm.html.twig', [
            'user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }
}