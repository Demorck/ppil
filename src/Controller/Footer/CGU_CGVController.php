<?php

declare(strict_types=1);

namespace App\Controller\Footer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CGU_CGVController extends AbstractController
{
    #[Route('/cgu-cgv')]
    public function index(): Response
    {
        return $this->render('footer_link/cgu_cgv.html.twig');
    }
}
