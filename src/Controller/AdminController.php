<?php

namespace App\Controller;

use App\Entity\Infos;
use App\Entity\Project;
use App\Form\InfosType;
use App\Form\ProjectType;
use App\Repository\InfosRepository;
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

    #[Route('/infos/new' , name:'infos_add' )]
    #[Route('/infos/{id}/edit' , name:'infos_edit' )]
    public function infos(Infos $infos = null, EntityManagerInterface $manager, InfosRepository $repo, Request $request, SluggerInterface $slugger)
    {
        if (!$infos) {
            if ($repo->findAll() === []) {
                $infos = new infos();
            } else {
                return $this->redirectToRoute('admin_infos_edit', ['id' => 1]);
            }
        }

        $form = $this->createForm(InfosType::class, $infos);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) 
        {
            if($form->get('photo')->getData() !== null) {
                if($infos->getId() !== null) {
                  unlink($this->getParameter('images_directory') .  '/' . $infos->getPhoto() );
                }

                $photo = $form->get('photo')->getData();

                if (in_array($photo->guessExtension(), ["png", "jpg", "jpeg", "PNG"])) {
                    $uploader = new UploadService($slugger, $this->getParameter('images_directory'));
                    $photoName = $uploader->upload($photo);
                    $infos->setPhoto($photoName);
                } else {
                    $this->addFlash('error' , 'Format de photo png et jpeg uniquement');
                    return $this->redirectToRoute('admin_infos_edit', [
                        'id' => 1
                    ]);
                }

            }
            if($form->get('cv')->getData() !== null) {
                if($infos->getId() !== null) {
                    unlink($this->getParameter('files_directory') .  '/' . $infos->getCV() );
                  }
                  $cv = $form->get('cv')->getData();

                  if(in_array($cv->guessExtension(), ["pdf" , "PDF"] )) {
                    $uploader = new UploadService($slugger, $this->getParameter('files_directory'));
                    $cvName = $uploader->upload($cv);
                    $infos->setCV($cvName);
                } else {
                    $this->addFlash('error' , 'Format de CV pdf uniquement');
                    return $this->redirectToRoute('admin_infos_edit', [
                        'id' => 1
                    ]);
                }
          }

          $manager->persist($infos);
          $manager->flush();
        }


        return $this->render('admin/infos.html.twig', [
            "infosForm" => $form->createView(),
            "infos" => $infos
        ]);

    }

}
