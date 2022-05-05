<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Media;
use App\Entity\Tricks;
use App\Entity\Users;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\TricksRepository;
use App\Services\MediaService;
use App\Services\TrickService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
    /**
     * @Route("/trick/new", name="newTrick")
     */
    public function new(Request $request, TrickService $trickService, TricksRepository $tricksRepository, SluggerInterface $slugger)
    {
        $trick = new Tricks();
        $form = $this->createForm(TrickType::class, $trick);
        $user = $this->getUser();

        if ($user === null) {
            $this->addFlash('error', 'Veuillez vous connecter pour ajouter un trick.');
            return $this->redirectToRoute('app_login');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = htmlspecialchars($form->getData()->getName());

            $nameExists = $trickService->findByName($name, $tricksRepository);

            if ($nameExists === true) {
                $this->addFlash('error', "Le trick existe déjà. Il n'a pas été ajouté.");
                return $this->redirectToRoute('app_home');
            }

            try {
                $trickService->addTrick($form, $request, $name, $trick, $user, $slugger);
            } catch (Exception $e) {
                $this->addFlash('error', "Le trick n'est pas conforme. Il n'a pas été ajouté.");
                return $this->redirectToRoute('app_home');
            }

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
    public function updateForm(Request $request, $trick_id, SluggerInterface $slugger, TrickService $trickService, TricksRepository $tricksRepository)
    {
        $trick = $this->getDoctrine()->getManager()->getRepository(Tricks::class)->find($trick_id);
        $form = $this->createForm(TrickType::class, $trick);
        $user = $this->getUser();

        if ($user === null) {
            $this->addFlash('error', 'Veuillez vous connecter pour modifier un trick.');
            return $this->redirectToRoute('app_login');
        }

        if (!$trick) {
            return $this->render('404.html.twig');
        }

        $form->handleRequest($request);

        //soumission du form
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $name = htmlspecialchars($form->getData()->getName());

            $nameExists = $trickService->findByName($name, $tricksRepository);

            if ($nameExists === true) {
                $this->addFlash('error', "Le nom de trick existe déjà. Il n'a pas été modifié.");
                return $this->redirectToRoute('app_home');
            }

            try {
                $trickService->updateTrick($form, $request, $name, $trick, $user, $tricksRepository, $slugger, $em);
            } catch (Exception $e) {
                $this->addFlash('error', "Le trick n'est pas conforme. Il n'a pas été ajouté.");
                return $this->redirectToRoute('app_home');
            }

            $this->addFlash('success', 'Le trick a bien été modifié.');
            return $this->redirectToRoute('app_home');
        }

        $user = $this->getUser();

        return $this->render('tricks/editTrick.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
            'trick' => $trick,
        ));
    }

    /**
     * @Route("/trick/{slug}", name="trick", methods={"GET"})
     */
    public function trick($slug)
    {
        $trick = $this->getDoctrine()->getManager()->getRepository(Tricks::class)->findBy(['slug' => $slug])[0];

        if (!$trick) {
            return $this->render('404.html.twig');
        }

        $comments = $trick->getComments()->getValues();
        $medias = $trick->getMedias()->getValues();
        $user = $this->getUser();

        return $this->render(
            'tricks/trick.html.twig',
            ['trick' => $trick, 'comments' => $comments, 'medias' => $medias, 'user' => $user]
        );
    }

    /**
     * @Route("/getTricks", name="getTrickAjax", methods={"POST"})
     */
    public function getTricks(Request $request)
    {
        $min = $request->request->get('min');
        $max = $request->request->get('max');
        $user = $request->request->get('user');

        $allTricks = $this->getDoctrine()
            ->getRepository(Tricks::class)->findAll();

        $tricks = $this->getDoctrine()
            ->getRepository(Tricks::class)
            ->findBy(array(), ['date_creation' => 'DESC'], 8, $min);

        $output['limit_offset'] = ['min' => $min + 8, 'max' => $max + 8];
        $output['result'] = [];
        $output['result']['totalTricks'] = count($allTricks);
        $output['result']['current'] = $max;

        foreach ($tricks as $trick) {
            $output['result']['tricks'][] = array(
                $trick->getId(),
                $trick->getName(),
                $trick->getImgBackground(),
                $trick->getDateCreation()->format(('d-m-Y à H:i:s')),
                $trick->getUser()->getId() ?? 1,
                $user,
                $trick->getSlug()
            );
        }

        return new JsonResponse($output);
    }

    /**
     * @Route("/getComments", name="getCommentsAjax", methods={"POST"})
     */
    public function getComments(Request $request)
    {
        $min = $request->request->get('min');
        $max = $request->request->get('max');
        $trickId = $request->request->get('trickId');

        $allComments = $this->getDoctrine()
            ->getRepository(Comments::class)
            ->findBy(['trick_id' => $trickId]);

        $comments = $this->getDoctrine()
            ->getRepository(Comments::class)
            ->findBy(['trick_id' => $trickId], ['created_at' => 'DESC'], 10, $min);

        $output['limit_offset'] = ['min' => $min + 10, 'max' => $max + 10];
        $output['result'] = [];

        foreach ($comments as $comment) {
            $userId = $this->getDoctrine()
                ->getManager()
                ->getRepository(Users::class)
                ->find($comment->getUser()->getId());

            $userPictUrl = $userId->getImage();

            $output['result']['commentaires'][] = array($comment->getMessage(), $comment->getCreatedAt()->format('d-m-Y à H:i:s'), $comment->getAuthor(),
                $userPictUrl);
        }

        $output['result']['total'] = count($allComments);
        $output['result']['current'] = $max;

        return new JsonResponse($output);
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

        $this->addFlash('success', 'Le trick a bien été supprimé.');

        return $this->redirectToRoute('app_home', [
            'tricks' => $tricks,
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/trick/comment/{slug}", name="commentTrick", methods={"GET", "POST"})
     */
    public function commentTrick(Request $request, $slug, TrickService $trickService)
    {
        $user_id = $this->getUser()->getId();
        $comment = new Comments();

        $trick = $trickService->getTrickBySlug($slug);

        if ($trick === false) {
            $this->addFlash('error', "La page que vous recherchez n'existe pas.");
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(CommentType::class, ['trick' => $trick, 'user_id' => $user_id]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $trickService->addComment($request, $form, $trick, $user_id, $comment);
            } catch (Exception $e) {
                $this->addFlash('error', "Le commentaire n'est pas conforme. Il n'a pas été ajouté.");
                return $this->redirectToRoute('app_home');
            }

            return $this->redirectToRoute('trick', ['slug' => $trick->getSlug()]);
        }

        if (!$trick) {
            return $this->render('404.html.twig');
        }

        return $this->render('comments/commentForm.html.twig', [
            'user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/trick/add-image", name="addImage", methods={"POST"})
     */
    public function addImage(Request $request, MediaService $mediaService)
    {
        $em = $this->getDoctrine()->getManager();
        $img = new Media();
        $mediaService->addMedia($request, $em, $img);

        return new JsonResponse(200, $img->getUrl());
    }
}
