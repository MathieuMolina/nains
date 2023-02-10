<?php

namespace App\Controller;

use App\Repository\TopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    #[Route('/forum', name: 'app_forum')]
    public function index(TopicRepository $topicRepository): Response
    {

        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
            'topics' => $topicRepository -> findAll(),
        ]);
    }
}
