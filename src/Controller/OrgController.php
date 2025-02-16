<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UpdateUserFormType;

final class OrgController extends AbstractController
{
    #[Route('/org', name: 'org')]
    public function index(): Response
    {
        return $this->render('org/org.html.twig');
    }
}