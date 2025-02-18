<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ControllerController extends AbstractController
{
    #[Route('/controller', name: 'app_controller')]
    public function index(): Response
    {
        return $this->render('controller/index.html.twig', [
            'controller_name' => 'ControllerController',
        ]);
    }
}
