<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'backend_dashboard')]
    public function index(): Response
    {
        return $this->render('@backend/dashboard/index.html.twig');
    }
}
