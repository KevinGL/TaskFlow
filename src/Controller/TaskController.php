<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'tasks')]
    public function index(): Response
    {
        if(!$this->isGranted("ROLE_USER"))
        {
            return $this->redirectToRoute("app_login");
        }
        
        return $this->render('task/index.html.twig',
        [
            //
        ]);
    }

    #[Route('/tasks/add', name: 'add_task')]
    public function addTask(): Response
    {
        if(!$this->isGranted("ROLE_ADMIN"))
        {
            return $this->redirectToRoute("tasks");
        }
        
        return $this->render('task/add.html.twig',
        [
            //
        ]);
    }
}
