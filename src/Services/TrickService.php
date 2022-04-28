<?php

namespace App\Services;

use App\Entity\Comments;
use App\Entity\Media;
use App\Entity\Tricks;
use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;
use function PHPUnit\Framework\throwException;

class TrickService extends AbstractController
{
    private $trickRepository;

    function __construct(TricksRepository $trickRepository)
    {
        $this->trickRepository = $trickRepository;
    }

    function addTrick($form, $request, $name, $trick, $user, TricksRepository $tricksRepository, SluggerInterface $slugger)
    {
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
        $slugger = new AsciiSlugger();
        $trick->setSlug($slugger->slug($name));

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
    }


    public function findByName(string $name, $trickRepository)
    {
        $trick_name_exist = $trickRepository->findBy(array('name' => $name));

        if (empty($trick_name_exist)) {
            return false;
        } else {
            return true;
        }

    }

    public function updateTrick(FormInterface $form, Request $request, string $name, $trick, UserInterface $user, TricksRepository $tricksRepository, SluggerInterface $slugger, $em)
    {
        $mediaToDeleteTab = $request->get('media_to_delete');

        if ($mediaToDeleteTab) {
            foreach ($mediaToDeleteTab as $med) {
                $mediaTodelete = $em->getRepository(Media::class)->find($med);
                $em->remove($mediaTodelete);
                $em->flush();
            }
        }

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
        $trick->setSlug($slugger->slug($user));

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
    }

    public function addComment(Request $request, FormInterface $form, $trick, $user_id, Comments $comment)
    {
        if ($form->get('message')->getData() === null) {
          throw new \Exception("Le commentaire n'est pas conforme.");
        }
        $comment->setMessage(htmlspecialchars($form->get('message')->getData()));
        $comment->setTrick($trick);
        $comment->setUser($this->getUser());
        $comment->setAuthor($this->getUser()->getEmail());
        $comment->setCreatedAt(new \DateTimeImmutable('now'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();
    }

    public function getTrick($id)
    {
        $trick = $this->getDoctrine()->getManager()->getRepository(Tricks::class)->find($id);

        if ($trick === null) {
            return false;
        }

        return $trick;
    }

    public function getTrickBySlug($slug)
    {
        $trick = $this->getDoctrine()->getManager()->getRepository(Tricks::class)->findOneBy(array('slug' => $slug));

        if ($trick === null) {
            return false;
        }

        return $trick;
    }
}
