<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }






    #[Route('/message/{message}', name: 'app_front_message_show')]
    public function showMessage(Request $request, Message $message): Response
    {
        $form = $this->newMessage($request, $message->getTopic(), $message);
        $otherMessages = $this->messageRepository->findAllByTopicParent($message->getTopic());
        return $this->render('home/show_message.html.twig', [
            'message' => $message,
            'otherMessages' => $otherMessages,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/topic/{topic}', name: 'app_front_topic_show')]
    public function showTopic(Request $request, Topic $topic, PaginatorInterface $paginator): Response
    {
        $form = $this->newMessage($request, $topic);
//        $messages = $this->messageRepository->findAllByTopicParent($topic);

        $pagination = $paginator->paginate(
            $this->messageRepository->findAllByTopicParentQueryBuilder($topic), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        return $this->render('home/show_topic.html.twig', [
            'topic' => $topic,
            //'messages' => $messages,
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    public function newMessage(Request $request, Topic $topic, Message $message = null): FormInterface
    {
        $newMessage = new Message();
        $newMessage->setDateCreated(new DateTime());
        $newMessage->setTopic($topic);
        $newMessage->setMessage(null);
        if ($message != null) {
            $title = $message->getTitle();
            $newMessage->setTitle($title);
            $newMessage->setMessage($message);
        }
        $newMessage->setUser($this->getUser());

        $form = $this->createForm(MessageType::class, $newMessage);
        if ($message != null) {
            $form->remove('title');
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->messageRepository->save($newMessage, true);
            $this->addFlash('success', 'Votre message a été enregistrée');
            $form = $this->createForm(MessageType::class, $newMessage);
        }

        return $form;
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route("/contact", name: "app_contact")]
    public function sendMail(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createFormBuilder()
            ->add('nom', TextType::class)
            ->add('email', EmailType::class)
            ->add('content', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Message'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $data = $form->getData();
            $mail = (new Email())
                ->from($data['email'])
                ->to(new Address('contact@drosalys.fr', 'Alexandre'))
                ->subject('Email de contact')
                ->html('<p>' . $data['content'] . '</p>');

            $mailer->send($mail);
            $this->addFlash("success", "Votre message a bien été envoyée");
        }

        return $this->render('home/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/search', name: 'app_search')]
    public function recherchePost(Request $request, PaginatorInterface $paginator): Response
    {
        $formR = $this->createForm(SearchType::class);
        $pagination = [];

        $formR->handleRequest($request);
        if ($formR->isSubmitted() && $formR->isValid()) {
            $title = $formR->getData()['title'];
            $pagination = $paginator->paginate(
                $this->messageRepository->findByTitle($title), /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                5/*limit per page*/
            );
        }

        return $this->render('home/show_topic.html.twig', [
            'formR' => $formR->createView(),
            'pagination' => $pagination,
        ]);
    }

}