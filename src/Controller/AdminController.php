<?php

namespace App\Controller;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/projects', name: 'projects')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/project/new', name:'add_project')]
    #[Route('/project/{id}/edit', name:'edit_project')]
    public function project( Request $request, EntityManagerInterface $manager, Project $project) {

        if($project === null) {
            $project = new Project();
        }

        $form = $this->createForm(Project::class, $project);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
        }

    }
}
