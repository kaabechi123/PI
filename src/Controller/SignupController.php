<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use App\Entity\User;
use App\Form\SignupFormType;
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
    public function index(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $logger->info('SignupController: Request received for signup.');
    
        $user = new User();
        $form = $this->createForm(SignupFormType::class, $user);
    
        $logger->debug('SignupController: Form created.', ['form_data' => $form->getData()]);
    
        // Handle form submission
        $form->handleRequest($request);
    
        // Log form submission state
        if ($form->isSubmitted()) {
            $logger->info('SignupController: Form submitted.', ['form_data' => $form->getData()]);
        }
    
        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $logger->info('SignupController: Form is valid.', ['form_data' => $form->getData()]);
    
            // Hash the password before saving
            $password = $form->get('password')->getData();
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
    
            // Set the role from the form data
            $role = $form->get('role')->getData();
            $user->setRole($role);
    
            $logger->debug('SignupController: User entity prepared for persistence.', ['user' => $user]);
    
            // Persist the user in the database
            $entityManager->persist($user);
            $entityManager->flush();
    
            $logger->info('SignupController: User persisted successfully.', ['user_id' => $user->getId()]);
    
            // Redirect to login page after successful signup
            return $this->redirectToRoute('login');
        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $errors = [];
            
            // Loop through all form errors (including child forms)
            foreach ($form->getErrors(true, true) as $error) {
                $fieldName = $error->getOrigin()->getName(); // Get the field name
                $errors[] = [
                    'field' => $fieldName,
                    'message' => $error->getMessage(),
                ];
            }
        
            // Log the errors
            $logger->error('SignupController: Form is invalid.', ['errors' => $errors]);
        
            // Log the raw form data for debugging
            $formData = $form->getData();
            $logger->debug('SignupController: Request method.', ['method' => $request->getMethod()]);
$logger->debug('SignupController: Request data.', ['data' => $request->request->all()]);
            $logger->debug('SignupController: Form data on invalid submission.', ['form_data' => $formData]);
            $logger->debug('SignupController: Raw request data.', ['request_data' => $request->request->all()]);
        }
    
        // Render the signup form template
        return $this->render('signup/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
