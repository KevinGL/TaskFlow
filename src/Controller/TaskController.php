<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Date;

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
    public function addTask(EntityManagerInterface $em, Request $req): Response
    {
        if(!$this->isGranted("ROLE_ADMIN"))
        {
            return $this->redirectToRoute("tasks");
        }

        $task = new Task();

        $form = $this->createForm(TaskFormType::class, $task);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid())
        {
            $task->setCreatedAt(new \DateTimeImmutable());
            $task->setUser($this->getUser());
            
            $em->persist($task);
            $em->flush();

            $this->addFlash("success", "La tâche a bien été ajoutée");
            return $this->redirectToRoute("tasks");
        }
        
        return $this->render('task/add.html.twig',
        [
            "form" => $form
        ]);
    }
}
