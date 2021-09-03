<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security")
     */

     public function registration(Request $request, EntityManagerInterface $Manager,
     UserPasswordEncoderInterface $encoder){
         $user = new User();

         $form = $this->createForm(RegistrationType::class, $user);

         $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid()){
             $hash =$encoder->encodePassword($user, $user->getPassword());
             $user->setUsername($user->getEmail());

             $user->setPassword($hash);

             $Manager->persist($user);
             $Manager->flush();

             return $this->redirectToRoute('security_login');
         }

         return $this->render('security/registration.html.twig', [
             'form' => $form->createView()
         ]);
     }

        /**
         * @Route("/connexion", name="security_login")
         */

        public function login() {
            return $this->render('security\login.html.twig');
        }

        /**
         * @Route("/deconnexion", name="security_logout")
         */

         public function logout() {}
}