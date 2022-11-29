<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin', 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/projects', name: 'projects')]
    public function projects(ProjectRepository $repo): Response
    {
        $projects = $repo->findAll();

        return $this->render('admin/projects.html.twig', [
            'projects' => $projects
        ]);
    }

    #[Route('/project/new', name: 'add_project')]
    #[Route('/project/{id}/edit', name: 'edit_project')]
    public function project(Project $project = null,  Request $request, EntityManagerInterface $manager, SluggerInterface $slugger)
    {
        $editMode = true;
        if (!$project) {
            $project = new Project();
            $editMode = false;
        }

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            if ($form->get('image')->getData() !== null) {
                if ($project->getId() !== null) {
                    unlink($this->getParameter('images_directory') . '/' . $project->getImage());
                }
                $image = $form->get('image')->getData();
                
                if (in_array($image->guessExtension(), ["png", "jpg", "jpeg", "PNG"])) {
                    $uploader = new UploadService($slugger, $this->getParameter('images_directory'));
                    $imageName = $uploader->upload($image);
                    $project->setImage($imageName);
                }
                
            }
            $manager->persist($project);
            $manager->flush();
            return $this->redirectToRoute('admin_projects');
        }
        return $this->render('admin/project.html.twig', [
            'projectForm' => $form->createView(),
            'editMode' => $editMode,
            'project' => $project
        ]);
    }

    #[Route('/project/remove/{id}' , name:'project_remove' )]
    public function removeProject (Project $project, EntityManagerInterface $manager) {
        unlink($this->getParameter('images_directory') . '/' . $project->getImage());
        $manager->remove($project);
        $manager->flush();

        return $this->redirectToRoute('admin_projects');
    }
}
