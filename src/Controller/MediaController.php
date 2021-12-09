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
     * @Route("/media/add", name="addMedia")
     */
    public function new(Request $request, SluggerInterface $slugger)
    {
        $media = new Media();
        $trick = $this->getDoctrine()->getManager()->getRepository(Tricks::class)->find($request->get('id'));
        if($trick === null){
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
                    $file->move(
                        $this->getParameter('trickFiles'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    return $e;
                }
            } elseif ($form->getData()->getType() === "VID") {
                $media->setUrl($form->getData()->getUrlVideo());
            } else {
                return false;
            }
            $media->setCreatedAt(new \DateTimeImmutable('now'));
            $media->setTrick($trick);
            $em = $this->getDoctrine()->getManager();
            $em->persist($media);
            $em->flush();

            return $this->redirectToRoute('trick',['id' => $trick->getId()]);
        }

        return $this->render('medias/newMedia.html.twig', array(
            'form' => $form->createView(),
            'message' => $message ?? null
        ));
    }

}