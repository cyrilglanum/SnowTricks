<?php


namespace App\Controller;

use App\Entity\Tricks;
use App\Form\TrickType;
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
                    // ... handle exception if something happens during file upload
                }
            }

            $trick->setDescription($form->get('description')->getData());
            $trick->setDateCreation(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();
        }

        return $this->render('tricks/newTrick.html.twig', array(
            'form' => $form->createView(),
            'message' => "le trick a bien été ajouté."
        ));
    }


    /**
     * @Route("/editTrick/{trick_id}", name="editTrick", methods={"GET"})
     */
    public function editTrick(Request $request,$trick_id)
    {
        dd('trick_view', $trick_id);

        return $this->render('tricks/newTrick.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/trick/{trick_id}", name="trick", methods={"GET"})
     */
    public function trick(Request $request,$trick_id)
    {
        dd('trick_view', $trick_id);

        return $this->render('tricks/newTrick.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}