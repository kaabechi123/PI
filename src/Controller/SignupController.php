<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class SignupController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/signup', name: 'signup', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        if ($request->isMethod('POST')) {
            $password = $request->request->get('password');

            $user->setUsername($request->request->get('username'));
            $user->setEmail($request->request->get('email'));
            $user->setRole($request->request->get('role'));  // Set role from dropdown
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('login'); // Redirect after success
        }

        return $this->render('signup/signup.html.twig');
    }
}