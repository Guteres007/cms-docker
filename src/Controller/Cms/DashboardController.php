<?php


namespace App\Controller\Cms;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', 'admin-dashboard')]
    public function index()
    {
        return $this->render('cms/dashboard/index.html.twig');
    }
}
