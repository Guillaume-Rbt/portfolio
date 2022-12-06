<?php

namespace App\Controller;

use App\Repository\InfosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(InfosRepository $repoInfos): Response
    {
        $infos = $repoInfos->findAll()[0];
        
        return $this->render('front/index.html.twig', [
            'infos' => $infos
        ]);
    }
}
