<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskFormType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'tasks')]
    public function index(TaskRepository $repo): Response
    {
        if(!$this->isGranted("ROLE_USER"))
        {
            return $this->redirectToRoute("app_login");
        }

        $tasks = $repo->findBy(['user' => $this->getUser()]);
        
        return $this->render('task/index.html.twig',
        [
            "tasks" => $tasks
        ]);
    }

    #[Route('/tasks/read', name: 'read_tasks')]
    public function read(TaskRepository $repo): Response
    {
        if(!$this->isGranted("ROLE_ADMIN"))
        {
            return $this->redirectToRoute("tasks");
        }

        $tasks = $repo->findAll();
        
        return $this->render('task/read.html.twig',
        [
            "tasks" => $tasks
        ]);
    }

    #[Route('/tasks/add', name: 'add_task')]
    public function addTask(EntityManagerInterface $em, Request $req, UserRepository $repo): Response
    {
        if(!$this->isGranted("ROLE_ADMIN"))
        {
            return $this->redirectToRoute("tasks");
        }

        $task = new Task();

        $task->setCreatedAt(new \DateTime());

        $form = $this->createForm(TaskFormType::class, $task);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid())
        {
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

    #[Route('/tasks/edit/{id}', name: 'task_edit')]
    public function edit(Request $req, EntityManagerInterface $em, TaskRepository $repo, int $id): Response
    {
        if(!$this->isGranted("ROLE_ADMIN"))
        {
            return $this->redirectToRoute("tasks");
        }

        $task = $repo->find($id);

        $form = $this->createForm(TaskFormType::class, $task);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute("read_tasks");
        }
        
        return $this->render('task/edit.html.twig',
        [
            "task" => $task,
            "form" => $form
        ]);
    }

    #[Route('/tasks/delete/{id}', name: 'task_delete')]
    public function delete(TaskRepository $repo, EntityManagerInterface $em, int $id): Response
    {
        if(!$this->isGranted("ROLE_ADMIN"))
        {
            return $this->redirectToRoute("tasks");
        }

        $task = $repo->find($id);

        $em->remove($task);
        $em->flush();
        
        return $this->redirectToRoute("read_tasks");
    }

    #[Route('/tasks/valid/{id}', name: 'valid_task')]
    public function valid(TaskRepository $repo, EntityManagerInterface $em, int $id): Response
    {
        if(!$this->isGranted("ROLE_USER"))
        {
            return $this->redirectToRoute("app_login");
        }

        $task = $repo->find($id);

        $task->setTreatedAt(new \DateTime());

        $em->persist($task);
        $em->flush();
        
        return $this->redirectToRoute("tasks");
    }
}
