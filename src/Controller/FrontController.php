<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Repository\InfosRepository;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front')]
    public function index(InfosRepository $infosRepository, ProjectRepository $projectRepository): Response
    {
        $infos = $infosRepository->findAll()[0];
        $projects = $projectRepository->findAll();
        $contactForm = $this->createForm(ContactType::class);

        $now = new \DateTime(date('Y-m-d'));
        $birth = date_create_from_format('d/m/Y', $infos->getBirth());
        $age = $now->diff($birth, true)->format('%Y');

        return $this->render('front/index.html.twig', [
            'infos' => $infos,
            'age' => $age,
            'contactForm' => $contactForm->createView(),
            'projects' => $projects
        ]);
    }

    #[Route('/message', name: 'app_message', methods: 'POST')]
    public function handleSendMessage(Request $request)
    {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
    }
}
