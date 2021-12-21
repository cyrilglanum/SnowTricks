<?php


namespace App\Controller;


use App\Entity\Media;
use App\Entity\Tricks;
use App\Form\AddMediaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class MediaController extends AbstractController
{
    /**
     * @Route("/media/add/{id}", name="addMedia")
     */
    public function new(Request $request, SluggerInterface $slugger, Tricks $trick)
    {
        $media = new Media();
        if ($trick === null) {
            return $this->redirect('/');
        }
        $form = $this->createForm(AddMediaType::class, $media);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $media->setDescription($form->getData()->getDescription());
            $media->setType($form->getData()->getType());
            if ($form->getData()->getType() === "IMG") {
                $file = $form->get('url')->getData();
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                $media->setUrl($newFilename);
                // Move the file to the directory where brochures are stored
                try {
                    if (str_contains('ocprojects.fr', $_SERVER['HTTP_HOST'])) {
                        $file->move(
                            $this->getParameter('prodTrickFiles'),
                            $newFilename
                        );
                    } else {
                        $file->move(
                            $this->getParameter('trickFiles'),
                            $newFilename
                        );
                    }

                } catch (FileException $e) {
                    return $e;
                }
            } elseif ($form->getData()->getType() === "VID") {
                if(str_contains($form->getData()->getUrlVideo(),"https://youtu.be")){
                    $media->setUrl("https://www.youtube.com/embed/".explode("/",$form->getData()->getUrlVideo())[3]);
                }else{
                    $this->addFlash('error', 'Il y a eu une erreur lors de l\'ajout du contenu!');
                    return $this->redirectToRoute('app_home', ['message' => 'Le téléchargement de fichier n\'a pas pu aboutir']);
                }
            } else {
                return false;
            }
            $media->setCreatedAt(new \DateTimeImmutable('now'));
            $media->setTrick($trick);
            $em = $this->getDoctrine()->getManager();
            $em->persist($media);
            $em->flush();

            $this->addFlash('success', 'Le média a bien été ajouté.');

            return $this->redirectToRoute('trick', ['id' => $trick->getId()]);
        }

        return $this->render('medias/newMedia.html.twig', array(
            'form' => $form->createView(),
            'message' => $message ?? null
        ));
    }

    /**
     * @Route("/media/delete/{trick_id}/{media_id}", name="deleteMedia")
     */
    public function deleteMedia($trick_id,$media_id)
    {
        if ($media_id === null) {
            return $this->redirect('/');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $media = $entityManager->getRepository(Media::class)->find($media_id);
        $entityManager->remove($media);
        $entityManager->flush();

        $trick = $entityManager->getRepository(Tricks::class)->find($trick_id);

        return $this->redirectToRoute('trick', ['id' => $trick->getId()]);
    }

}