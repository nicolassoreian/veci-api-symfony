<?php

namespace App\Controller;

use App\Form\LoginUserFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(
        private EmailVerifier $emailVerifier,
        private Security $security
    ) {
    }

    #[Route('/admin/login', name: 'backend_login')]
    public function index(AuthenticationUtils $authenticationUtils, TranslatorInterface $translator, Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('backend_dashboard');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            $this->addFlash('warning', $translator->trans($error->getMessage(), [], 'security'));
        }

        $form = $this->createForm(LoginUserFormType::class);
        $form->handleRequest($request);

        return $this->render('@backend/authentication/login/index.html.twig', [
             'form' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->get('id'); // retrieve the user id from the url

        // Verify the user id exists and is not null
        if (null === $id) {
            $this->addFlash('warning', 'Your validation link is invalid.');
            return $this->redirectToRoute('app_home');
        }

        $user = $userRepository->find($id);

        // Ensure the user exists in persistence
        if (null === $user) {
            $this->addFlash('warning', 'Your validation link is invalid.');
            return $this->redirectToRoute('app_home');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('danger', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_home');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        $this->security->login($user, 'form_login');

        return $this->redirectToRoute('app_register_completed');
    }


    #[Route('/admin/logout', name: 'backend_logout', methods: ['GET'])]
    public function logout()
    {
    }
}
