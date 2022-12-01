<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/', name: 'security_')]
class SecurityController extends AbstractController
{
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }


    #[Route('/registration' , name:'registration')]
    public function registration (User $user = null, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher, Request $request ) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user , $user->getPassword()));
            $manager->persist($user);
            $manager->flush();
        }

        return $this->render('security/registration.html.twig', [
            'userForm' => $form->createView()
        ]);
    }

    #[Route('/login', name:'login')]
    public function login() {
        
        return $this->render('security/login.html.twig');
    }

    #[Route('/logout', name:'logout')]
    public function logout() {
       
    }
}
