<?php


namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Media;
use App\Entity\Tricks;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Form\TrickUpdateType;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
        $message = null;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setName($form->getName());
            $brochureFile = $form->get('img_background')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                $trick->setImgBackground($newFilename);

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('trickFiles'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    return $e;
                }
            }

            $trick->setDescription($form->get('description')->getData());
            $trick->setDateCreation(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();
            $message = "le trick a bien été ajouté.";
        }


        return $this->render('tricks/newTrick.html.twig', array(
            'form' => $form->createView(),
            'message' => $message ?? null
        ));
    }


    /**
     * @Route("/Trick/editForm/{trick_id}", name="editTrick")
     */
    public function updateForm(Request $request, $trick_id, SluggerInterface $slugger)
    {
        $trick = $this->getDoctrine()->getManager()->getRepository(Tricks::class)->find($trick_id);
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        //soumission du form
        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setName($form->getData()->getName());
            $brochureFile = $form->get('img_background')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                $trick->setImgBackground($newFilename);

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('trickFiles'),
                        $newFilename
                    );
                    dd($this);
                } catch (FileException $e) {
                    return $e;
                }
            }

            $trick->setDescription($form->get('description')->getData());
            $trick->setDateModification(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('tricks/editTrick.html.twig', array(
            'form' => $form->createView(),
            'trick' => $trick,
            'message' => '',
        ));
    }

    /**
     * @Route("/Trick/{id}", name="trick", methods={"GET"})
     */
    public function trick(Request $request, $id)
    {
        $trick = $this->getDoctrine()->getManager()->getRepository(Tricks::class)->find($id);
        $comments = $trick->getComments()->getValues();
        $medias = $trick->getMedias()->getValues();

        return $this->render('tricks/trick.html.twig', ['trick' => $trick, 'comments' => $comments, 'medias' => $medias]
        );
    }

    /**
     * @Route("/trick/delete/{id}", name="deleteTrick", methods={"GET"})
     */
    public function deleteTrick($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $trick = $entityManager->getRepository(Tricks::class)->find($id);
        $entityManager->remove($trick);
        $entityManager->flush();

        $tricks = $entityManager->getRepository(Tricks::class)->findAll();

        return $this->render('index/index.html.twig', [
            'controller_name' => 'BlogController',
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
            $comment->setMessage($form->get('message')->getData());
            $comment->setTrick($trick);
            $comment->setUser($this->getUser());
            $comment->setAuthor($this->getUser()->getEmail());
            $comment->setCreatedAt(new \DateTimeImmutable('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('trick', ['id' => $trick->getId()]);
        }

        return $this->render('comments/commentForm.html.twig', [
            'user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }
}