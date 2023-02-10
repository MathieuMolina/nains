<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Form\TopicType;
use App\Repository\TopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/topic')]
class TopicController extends AbstractController
{
    #[Route('/', name: 'app_topic_index', methods: ['GET'])]
    public function index(TopicRepository $topicRepository): Response
    {
        return $this->render('topic/index.html.twig', [
            'topics' => $topicRepository->findAll(),
            'views' => $topicRepository-> findAll(),
            ]);
    }

    #[Route('/new', name: 'app_topic_new', methods: ['GET', 'POST'])]

//    Paragraphe qui suit pour capturer automatiquement la date actuelle lors de la création du topic

//    public function createAction(Request $request)
//    {
//        // Le formulaire symfony se chargera d'hydrater ton input date avec la valeur du champ date de l'entité article
//        $form = $this->createFormBuilder(new Topic()); //nul besoin de set la date grâce au constructeur
//        // ...
//    }


    public function new(Request $request, TopicRepository $topicRepository): Response
    {


//        Set ici ce que je ne propose pas dans le formulaire

        $topic = new Topic();
//        $topic->setTopicCreator(get_current_user());

        $date = new \DateTime();

        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Permet de set la date automatiquement =>
            $topic->setTopicDate($date);



            $topicRepository->save($topic, true);

            return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('topic/new.html.twig', [
            'topic' => $topic,
            'form' => $form,
        ]);


    }

    #[Route('/{id}', name: 'app_topic_show', methods: ['GET'])]
    public function show(Topic $topic): Response
    {
        return $this->render('topic/show.html.twig', [
            'topic' => $topic,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_topic_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Topic $topic, TopicRepository $topicRepository): Response
    {
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $topicRepository->save($topic, true);

            return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('topic/edit.html.twig', [
            'topic' => $topic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_topic_delete', methods: ['POST'])]
    public function delete(Request $request, Topic $topic, TopicRepository $topicRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$topic->getId(), $request->request->get('_token'))) {
            $topicRepository->remove($topic, true);
        }

        return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
    }
}
