<?php

namespace App\Controller;

use App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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

        //        Rajouter la logique pour créer un nouveau message

//        $form = $this->newMessage($request, $message->getTopic(), $message);



        return $this->render('topic/show.html.twig', [
            'topic' => $topic,
        ]);

    }






//    #[Route('/message/{message}', name: 'app_front_message_show')]
//    public function showMessage(Request $request, Message $message): Response
//    {
//        $form = $this->newMessage($request, $message->getTopic(), $message);
//        $otherMessages = $this->messageRepository->findAllByCategoryParent($message->getTopic());
//        return $this->render('home/show_message.html.twig', [
//            'message' => $message,
//            'otherMessages' => $otherMessages,
//            'form' => $form->createView(),
//        ]);
//    }

//    public function newMessage(Request $request, Topic $topic, Message $message = null): FormInterface
//    {
//        $newMessage = new Message();
//        $newMessage->setDateCreated(new DateTime());
//        $newMessage->setTopic($topic);
//        $newMessage->setMessage(null);
//        if ($message != null) {
//            $title = $message->getTitle();
//            $newMessage->setTitle($title);
//            $newMessage->setMessage($message);
//        }
//        $newMessage->setUser($this->getUser());
//
//        $form = $this->createForm(MessageType::class, $newMessage);
//        if ($message != null) {
//            $form->remove('title');
//        }
//
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->messageRepository->save($newMessage, true);
//            $this->addFlash('success', 'Votre message a été enregistrée');
//            $form = $this->createForm(MessageType::class, $newMessage);
//        }
//
//        return $form;
//    }

}
