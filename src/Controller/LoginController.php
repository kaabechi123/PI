<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;
use Psr\Log\LoggerInterface;

final class LoginController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils, Security $security): Response
    {
        // Log the last username and authentication error
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        $this->logger->info('Login attempt for user: ' . $lastUsername);
        if ($error) {
            $this->logger->error('Authentication error: ' . $error->getMessageKey());
        }

        // Log the current user's authentication status and roles
        $user = $security->getUser();
        if ($user) {
            $this->logger->info('User is authenticated: ' . $user->getUserIdentifier());
            $this->logger->info('User roles: ' . implode(', ', $user->getRoles()));
        } else {
            $this->logger->info('No authenticated user.');
        }

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

}