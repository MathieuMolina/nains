<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TopicRepository;
use App\Entity\Topic;
use App\Entity\User;
use App\Form\TopicType;


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


    #[Route('/new', name: 'app_topic_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TopicRepository $topicRepository): Response
    {
        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $topicRepository->save($topic, $user, true);


            return $this->redirectToRoute('app_forum', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('topic/new.html.twig', [
            'topic' => $topic,
            'form' => $form,
        ]);
    }

//    /**
//     * @Route("/forum/topic/{id}", name="forum_topic")
//     */
//    public function afficherTopic($id)
//    {
//        // Récupérer le sujet avec l'ID donné
//        $topic = // ...
//
//    // Afficher le sujet
//    return $this->render('forum/topic.html.twig', [
//        'topic' => $topic,
//    ]);
//}
}
