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

//    #[Route('/topic/{id}', name: 'topic/show.html.twig')]
//    public function show(string $id): Response
//    {
//        // Récupération du topic avec l'identifiant $id
//        $topic = $this->entityManager->getManager()->getRepository(Topic::class)->find($id);
//
//        // Vérification si le topic existe
//        if (!$topic) {
//            throw $this->createNotFoundException('Le topic n\'existe pas.');
//        }
//
//        // Création de la réponse
//        $response = new Response();
//
//        // Retour de la réponse
//        return $response;
//    }

    #[Route('/topic/{id}', name: 'app_topic_show')]
    public function show(Topic $topic): Response
    {

//         Vérification si le topic existe
        if (!$topic) {
            throw $this->createNotFoundException('Le topic n\'existe pas.');
        }

        return $this->render('topic/show.html.twig', [
            'topic' => $topic,
        ]);
    }


}
