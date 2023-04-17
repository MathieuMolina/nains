<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
    
        if ($this->getUser()){
            return $this->redirectToRoute('app_centre');
        }
        //  ^ retourne l'utilisateur connecté afin de l'empêcher de retourner sur la page de connexion/inscription
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
        
    }
}
