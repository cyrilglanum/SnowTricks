<?php

namespace App\Services;

use App\Entity\Tricks;
use App\Repository\MediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;

class MediaService extends AbstractController
{
    private $mediaService;

    function __construct(MediaRepository $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function addMedia(Request $request, $em, $img)
    {
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

        $img->setUrl($file);
        $img->setType('IMG');
        $img->setDescription('');
        $img->setCreatedAt(new \DateTimeImmutable('now'));
        $img->setTrick(1);
        $em->persist($img);
        $em->flush();



    }

    public function getTrick($id)
    {
        $trick = $this->getDoctrine()->getManager()->getRepository(Tricks::class)->find($id);

        if($trick === null){
            return false;
        }

            return $trick;
    }
}
