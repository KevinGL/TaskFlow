<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        if($this->getUser())
        {
            return $this->redirectToRoute("tasks");
        }
        
        return $this->render('home/index.html.twig',
        [
            //
        ]);
    }
}
