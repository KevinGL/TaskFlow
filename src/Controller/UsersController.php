<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class UsersController extends AbstractController
{
    #[Route('/users', name: 'app_users')]
    public function index(UserRepository $repo): Response
    {
        if(!$this->getUser())
        {
            return $this->redirectToRoute("app_login");
        }
        
        if(!in_array("ROLE_ADMIN", $this->getUser()->getRoles()))
        {
            return $this->redirectToRoute("tasks");
        }

        $users = $repo->findAll();
        
        return $this->render('users/index.html.twig',
        [
            'users' => $users,
        ]);
    }
}
