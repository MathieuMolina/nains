<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TopicRepository;

class ForumController extends AbstractController
{
    #[Route('/forum', name: 'app_forum')]
    public function  index(TopicRepository $topicRepository ): Response
    {

        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
            'topics' => $topicRepository -> findAll(),
        ]);
    }
}
