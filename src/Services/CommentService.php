<?php

namespace App\Services;

use App\Entity\Comments;
use App\Entity\Media;
use App\Entity\Tricks;
use App\Entity\Users;
use App\Form\CommentType;
use App\Form\Comment0Type;
use App\Form\UserUpdateType;
use App\Repository\CommentsRepository;
use App\Repository\TricksRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

class CommentService extends AbstractController
{
    private $commentService;

    function __construct(CommentsRepository $commentService)
    {
        $this->mediaService = $commentService;
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
