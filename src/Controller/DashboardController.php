<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UpdateUserFormType;
use Psr\Log\LoggerInterface; 


final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/dashboard.html.twig');
    }

    #[Route('/dashboard/users', name: 'dashboard_users')]
    public function users(UserRepository $userRepository): Response
    {
        // Fetch all users from the database
        $users = $userRepository->findAll();

        // Pass the users to the Twig template
        return $this->render('dashboard/listusers.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/dashboard/users/delete/{id}', name: 'dashboard_users_delete', methods: ['GET'])]
    public function deleteUser(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        // Find the user by ID
        $user = $userRepository->find($id);

        if ($user) {
            // Delete the user using the EntityManager
            $entityManager->remove($user);
            $entityManager->flush();

            // Add a flash message for success
            $this->addFlash('success', 'User deleted successfully.');
        } else {
            // Add a flash message for error
            $this->addFlash('error', 'User not found.');
        }

        // Redirect back to the users list
        return $this->redirectToRoute('dashboard_users');
    }

    #[Route('/dashboard/updateuser/{id}', name: 'updateuser', methods: ['GET', 'POST'])]
    public function updateuser(
        int $id,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger // Inject the logger service
    ): Response {
        // Log the start of the method
        $logger->info('UpdateUser: Method started for user ID {id}', ['id' => $id]);
    
        // Find the user by ID
        $user = $userRepository->find($id);
    
        if (!$user) {
            $logger->error('UpdateUser: User not found for ID {id}', ['id' => $id]);
            throw $this->createNotFoundException('User not found');
        }
    
        // Log that the user was found
    
        // Create the form
        $form = $this->createForm(UpdateUserFormType::class, $user);
    
        // Handle form submission
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            
    
                // Save the updated user to the database
                $entityManager->flush();
    
                // Add a flash message for success
                $this->addFlash('success', 'User updated successfully.');
    
                // Redirect back to the users list
                return $this->redirectToRoute('dashboard_users');
             
        }
    
        // Render the update form
        return $this->render('dashboard/updateuser.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}