<?php

namespace App\Controller;

use App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TopicRepository;
use App\Repository\MessageRepository;
use App\Entity\Topic;
use App\Entity\User;
use App\Form\MessageType;
use App\Form\TopicType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;


class ForumController extends AbstractController
{
    #[Route('/forum', name: 'app_forum')]
    public function index(
        TopicRepository $topicRepository,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {
        // Pagination pour topics:
        $topics = $paginator->paginate(
            $topicRepository->queryAll(), // Collection de topics à paginer
            $request->query->getInt('page', 1), // Numéro de page
            2 // Nombre de topics par page
        );


        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
            'topics' => $topics,
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

    #[Route('/topic/{id}', name: 'app_topic_show')]
    public function show(
        Request $request,
        Topic $topic,
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        MessageRepository $messageRepository,
    ): Response {
        // Vérification si le topic existe
        if (!$topic) {
            throw $this->createNotFoundException('Le topic n\'existe pas.');
        }

        // Gestion réponses
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setTopic($topic);
            $message->setUser($this->getUser());
            $em->persist($message);
            $em->flush();
        }

        // Pagination pour messages réponses:
        $messages = $paginator->paginate(
            $messageRepository->queryAll(), // Collection de messages à paginer
            $request->query->getInt('page', 1), // Numéro de page
            2 // Nombre de messages par page
        );



        return $this->render('topic/show.html.twig', [
            'topic' => $topic,
            'form' => $form->createView(),
            'messages' => $messages // Passer la pagination à la vue
        ]);
    }
}   









// #[Route('/message/{message}', name: 'app_front_message_show')]
    // public function showMessage(Request $request, Message $message): Response
    // {
    //     $form = $this->newMessage($request, $message->getTopic(), $message);
    //     $otherMessages = $this->messageRepository->findAllByCategoryParent($message->getTopic());
    //     return $this->render('home/show_message.html.twig', [
    //         'message' => $message,
    //         'otherMessages' => $otherMessages,
    //         'form' => $form->createView(),
    //     ]);
    // }
