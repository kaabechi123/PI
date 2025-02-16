<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AppAuthenticator extends AbstractAuthenticator
{
    private $userRepository;
    private $urlGenerator;

    public function __construct(UserRepository $userRepository, UrlGeneratorInterface $urlGenerator)
    {
        $this->userRepository = $userRepository;
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): ?bool
    {
        
        // Log the route and method to verify the request is being handled correctly
        error_log("Checking if authentication is supported: " . $request->attributes->get('_route') . " | Method: " . $request->getMethod());

        return $request->attributes->get('_route') === 'login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        // Log the email and password values
        error_log("Attempting login with Email: $email | Password: $password");

        $user = $this->userRepository->findOneByEmail($email);
        if (!$user) {
            error_log("User not found with email: $email");
            throw new AuthenticationException('Invalid credentials');
        }

        // Log the password hash from the database
        error_log("Password hash from database for user $email: " . $user->getPassword());

        // Check if password matches
        $passwordMatch = password_verify($password, $user->getPassword());

        // Log the result of password verification
        error_log("Password verification result for email: $email | Result: " . ($passwordMatch ? 'Success' : 'Failure'));

        if (!$passwordMatch) {
            error_log("Password mismatch for email: $email");
            throw new AuthenticationException('Invalid credentials');
        }

        // Log successful user authentication
        error_log("User authenticated successfully: " . $user->getEmail());

        return new Passport(
            new UserBadge($email, function ($email) {
                return $this->userRepository->findOneByEmail($email);
            }),
            new PasswordCredentials($password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Log authentication success
        error_log("Authentication successful for user: " . $token->getUserIdentifier());
    
        // Check user role and determine the target route based on that
        $user = $token->getUser();
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $targetPath = 'dashboard'; // For example, redirect to admin dashboard
        } elseif (in_array('ROLE_ORGANISATION', $user->getRoles())) {
            $targetPath = 'org'; // Redirect to organisation dashboard
        } elseif (in_array('ROLE_DELIVERY', $user->getRoles())) {
            $targetPath = 'delivery'; // Redirect to delivery dashboard
        } elseif (in_array('ROLE_CLIENT', $user->getRoles())) {
            $targetPath = 'home'; // Redirect to home for clients
        } else {
            $targetPath = 'dashboard'; // Default fallback if no role matched
        }
    
        // Log the target path
        error_log("Redirecting user to: " . $targetPath);
    
        // Redirect to the correct page based on the role
        return new RedirectResponse($this->urlGenerator->generate($targetPath));
    }
    
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        // Log authentication failure and redirect URL
        error_log("Authentication failed: " . $exception->getMessage());
        return new RedirectResponse($this->urlGenerator->generate('login'));
    }
}
