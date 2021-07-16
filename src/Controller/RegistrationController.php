<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepository $userRepository
     * @param GuardAuthenticatorHandler $guard
     * @param LoginFormAuthenticator $login
     * @return Response
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository,
        GuardAuthenticatorHandler $guard,
        LoginFormAuthenticator $login,
    ): Response {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        $existingUser = $userRepository->findAll();
        if ($existingUser != null) {
            return $this->redirectToRoute('home');
        } else {
            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                if ($form->get('plainPassword')->getData() === $form->get('passwordCheck')->getData()) {
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                    );

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();
                    $this->addFlash(
                        'success',
                        'Félicitations! Bienvenue sur votre interface administrateur.'
                    );
                    // do anything else you need here, like send an email
                    return $guard->authenticateUserAndHandleSuccess($user, $request, $login, 'main');
                }
            }

            return $this->render('registration/register.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        }
    }
}
