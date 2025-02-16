<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ProfileType; // We'll create this form later
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{
    private $security;
    private $passwordHasher;
    private $entityManager;

    public function __construct(Security $security, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager; // Injecting Doctrine's entity manager
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $user = $this->security->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('profile/profile.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/update', name: 'app_profile_update', methods: ['POST'])]
    public function updateProfile(Request $request): Response
    {
        $user = $this->security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Handle form submission
        $username = $request->get('username');
        $email = $request->get('email');
        $password = $request->get('password');

        if ($username) {
            $user->setUsername($username);
        }

        if ($email) {
            $user->setEmail($email);
        }

        if ($password) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
        }

        // Save the updated user
        $this->entityManager->flush();

        $this->addFlash('success', 'Profile updated successfully!');

        return $this->redirectToRoute('app_profile');
    }
}
