<?php


namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Media;
use App\Entity\Tricks;
use App\Entity\Users;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\MediaRepository;
use App\Repository\TricksRepository;
use http\Client\Response;
use http\Env;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
    /**
     * @Route("/trick/new", name="newTrick")
     */
    public function new(Request $request, SluggerInterface $slugger, TricksRepository $tricksRepository)
    {
        $trick = new Tricks();
        $form = $this->createForm(TrickType::class, $trick);
        $user = $this->getUser();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $name = htmlspecialchars($form->getData()->getName());

            $trick_name_exist = $tricksRepository->findBy(array('name' => $name), $orderBy = null, $limit = null, $offset = null);

            if (!$trick_name_exist) {
                $trick->setName($name);
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
                $trick->setUser($user);

                $em = $this->getDoctrine()->getManager();

                $medias = $request->files->get('trick')['medias'] ?? null;
                $med = [];

                if ($medias !== null) {
                    foreach ($medias as $media) {
                        if ($media['mediaCollection'] === null) {
                            continue;
                        }
                        $file = md5(uniqid()) . '.' . $media['mediaCollection']->guessExtension();
                        $mediaToAdd = new Media();
                        $mediaToAdd->setUrl($file);
                        $mediaToAdd->setType('IMG');
                        $mediaToAdd->setCreatedAt(new \DateTimeImmutable('now'));
//                 Move the file to the directory where brochures are stored
                        try {
                            if ($this->getParameter('prodTrickFiles')) {
                                $media['mediaCollection']->move(
                                    $this->getParameter('prodTrickFiles'),
                                    $file
                                );
                            } else {
                                $media['mediaCollection']->move(
                                    $this->getParameter('trickFiles'),
                                    $file
                                );
                            }
                        } catch (FileException $e) {
                            return $e;
                        }
                        $med[] = $mediaToAdd;
                    }
                }

                $em->persist($trick);
                $em->flush();

                foreach ($med as $media) {
                    $media->setTrick($trick);
                    $em->persist($media);
                    $em->flush();
                }
                //ajout des vidéos embed lors de la création du trick

                $videos = $form->get('videos')->getData();

                $exploded_videos = explode(',', $videos);

                if ($exploded_videos[0] !== "") {
                    foreach ($exploded_videos as $video_string) {
                        $video = new Media();

                        if (str_contains($video_string, "https://youtu.be")) {
                            $video->setUrl("https://www.youtube.com/embed/" . explode("/", $video_string)[3]);
                        } else {
                            $this->addFlash('error', 'Il y a eu une erreur lors de l\'ajout du contenu!');
                            return $this->redirectToRoute('app_home', ['message' => 'Le téléchargement de fichier n\'a pas pu aboutir']);
                        }

                        $video->setType('VID');
                        $video->setTrick($trick);
                        $video->setDescription('');
                        $video->setUrlVideo($video_string);
                        $video->setCreatedAt(new \DateTimeImmutable('now'));
                        $em->persist($video);
                        $em->flush();
                    }
                }

                $this->addFlash('success', 'Le trick a bien été ajouté.');
                return $this->redirectToRoute('app_home');
            } else {
                $this->addFlash('error', 'Le trick existe déjà.');
                return $this->redirectToRoute('app_home');
            }
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
        $user = $this->getUser();

        if (!$trick) {
            return $this->render('404.html.twig');
        }

        $form->handleRequest($request);

        //soumission du form
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $mediaToDeleteTab = $request->get('media_to_delete');

            if ($mediaToDeleteTab) {
                foreach ($mediaToDeleteTab as $med) {
                    $mediaTodelete = $em->getRepository(Media::class)->find($med);
                    $em->remove($mediaTodelete);
                    $em->flush();
                }
            }

            $name = htmlspecialchars($form->getData()->getName());

            $trick->setName($name);
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
            $trick->setUser($user);


            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();

            $medias = $request->files->get('trick')['medias'] ?? null;
            $med = [];

            if ($medias !== null) {
                foreach ($medias as $media) {
                    if ($media['mediaCollection'] === null) {
                        continue;
                    }
                    $file = md5(uniqid()) . '.' . $media['mediaCollection']->guessExtension();
                    $mediaToAdd = new Media();
                    $mediaToAdd->setUrl($file);
                    $mediaToAdd->setType('IMG');
                    $mediaToAdd->setCreatedAt(new \DateTimeImmutable('now'));
//                 Move the file to the directory where brochures are stored
                    try {
                        if ($this->getParameter('prodTrickFiles')) {
                            $media['mediaCollection']->move(
                                $this->getParameter('prodTrickFiles'),
                                $file
                            );
                        } else {
                            $media['mediaCollection']->move(
                                $this->getParameter('trickFiles'),
                                $file
                            );
                        }
                    } catch (FileException $e) {
                        return $e;
                    }
                    $med[] = $mediaToAdd;
                }
            }

            $em->persist($trick);
            $em->flush();

            foreach ($med as $media) {
                $media->setTrick($trick);
                $em->persist($media);
                $em->flush();
            }

            //ajout des vidéos embed lors de la création du trick

            $videos = $form->get('videos')->getData();

            if ($videos) {
                $exploded_videos = explode(',', $videos);
                foreach ($exploded_videos as $video_string) {
                    $video = new Media();

                    if (str_contains($video_string, "https://youtu.be")) {
                        $video->setUrl("https://www.youtube.com/embed/" . explode("/", $video_string)[3]);
                    } else {
                        $this->addFlash('error', 'Il y a eu une erreur lors de l\'ajout du contenu!');
                        return $this->redirectToRoute('app_home', ['message' => 'Le téléchargement de fichier n\'a pas pu aboutir']);
                    }

                    $video->setType('VID');
                    $video->setTrick($trick);
                    $video->setDescription('');
                    $video->setUrlVideo($video_string);
                    $video->setCreatedAt(new \DateTimeImmutable('now'));
                    $em->persist($video);
                    $em->flush();
                }
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
     * @Route("/trick/{id}", name="trick", methods={"GET"})
     */
    public function trick($id)
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
            $output['result']['tricks'][] = array($trick->getId(), $trick->getName(), $trick->getImgBackground(), $trick->getDateCreation()->format(('d-m-Y à H:i:s')), $trick->getUser()->getId() ?? 1, $user);
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


    /**
     * @Route("/trick/add-image", name="addImage", methods={"POST"})
     */
    public function addImage(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $image = $request->files->get('upload');
        $file = md5(uniqid()) . '.' . $image->guessExtension();
        // Move the file to the directory where brochures are stored
        try {
            if ($this->getParameter('prodTrickFiles')) {
                $image->move(
                    $this->getParameter('prodTrickFiles'),
                    $file
                );
            } else {
                $image->move(
                    $this->getParameter('trickFiles'),
                    $file
                );
            }
        } catch (FileException $e) {
            return $e;
        }

        $img = new Media();
        $img->setUrl($file);
        $img->setType('IMG');
        $img->setDescription('');
        $img->setCreatedAt(new \DateTimeImmutable('now'));
        $img->setTrick(1);
        $em->persist($img);
        $em->flush();

        return new JsonResponse(200, $img->getUrl());

    }


}