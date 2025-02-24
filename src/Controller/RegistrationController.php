<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('app_recipe');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            // $plainPassword = $form->get('plainPassword')->getData();

            
            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $user->getPlainPassword()));
            

            $email = (new TemplatedEmail())
                ->from('test@test.com') // your email
                ->to($user->getEmail()) // user email
                ->subject('Welcome to the Space Bar!') // email subject
                ->htmlTemplate('emails/welcome_email.html.twig') // email template
                ->context([
                    'data' => $user,
                ]);

            $mailer->send($email);

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
