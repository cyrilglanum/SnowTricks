<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/trick/comment/{id}", name="commentTrick")
     */
    public function comment(Request $request, $id): Response
    {
        dd("r");
        return $this->render('comments/commentForm.html.twig', [
            'controller_name' => 'HelloController',
            'prenom' => $prenom
        ]);
    }
}
