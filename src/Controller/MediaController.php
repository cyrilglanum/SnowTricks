<?php


namespace App\Controller;


use App\Entity\Media;
use App\Entity\Tricks;
use App\Form\AddMediaType;
use App\Form\TrickType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class MediaController extends AbstractController
{
    /**
     * @Route("/media/add", name="addMedia")
     */
    public function new(Request $request, SluggerInterface $slugger)
    {
        $media = new Media();
        $form = $this->createForm(AddMediaType::class, $media);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {




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
//                    $brochureFile->move(
//                        $this->getParameter('trickFiles'),
//                        $newFilename
//                    );
//                } catch (FileException $e) {
//                    return $e;
//                }
//            }

//            $trick->setDescription($form->get('description')->getData());
//            $trick->setDateCreation(new \DateTime('now'));
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($trick);
//            $em->flush();
//            $message = "le trick a bien été ajouté.";
        }


        return $this->render('medias/newMedia.html.twig', array(
            'form' => $form->createView(),
            'message' => $message ?? null
        ));
    }

}