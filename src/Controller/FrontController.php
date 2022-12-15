<?php

namespace App\Controller;

use Symfony\Component\Mime\Address;
use App\Form\ContactType;
use App\Repository\InfosRepository;
use App\Repository\ProjectRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
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
    public function handleSendMessage(Request $request, MailerInterface $mailer)
    {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();

            if(empty($contactFormData['recaptchaResponse']))
            {
                $this->redirectToRoute('app_front');
            } else 
            {
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=SITE_KEY&response={$contactFormData["recaptchaResponse"]}";
                $response = file_get_contents($url);
            }

            if(empty($response) || is_null($response))
            {
                $this->redirectToRoute('app_front');
            } else {
                $data = json_decode($response);
            }

            if ($data->success) {
                $email = (new TemplatedEmail())
                ->to( new Address("contact@guillaume-robert-webdev.fr"))
                ->from('contact@guillaume-robert-webdev.fr')
                ->subject('Contact portfolio')
                ->htmlTemplate('email/contact.html.twig')
                ->context([
                    'lastname' => $contactFormData['lastname'],
                    'firstname' => $contactFormData['firstname'],
                    "email" => $contactFormData['email'],
                    'subject' => $contactFormData['subject'],
                    'message' => $contactFormData['message']
                ]);
                $mailer->send($email);
                $this->addFlash('mailSuccess', 'Votre message a bien été envoyé');
                $this->redirectToRoute('app_front');

            } else if (!$data->success) {
                $this->addFlash('mailError', 'Erreur reCAPTCHA veuillez recommencer');
                $this->redirectToRoute('app_front');

            }
        }
    }

    #[Route('/mentions-legales', name:'app_mentions-legales')]
    public function legalNotice (InfosRepository $infosRepository) {
        $infos = $infosRepository->findAll()[0];
        return $this->render('front/legal-notice.html.twig', [
            'infos' => $infos
        ]);
    }
}
