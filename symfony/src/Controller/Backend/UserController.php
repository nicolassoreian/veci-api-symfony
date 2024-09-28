<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]
#[IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    #[Route('/', name: 'backend_user_index', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator, UserRepository $userRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('backend_user_edit', ['id' => $this->getUser()], Response::HTTP_SEE_OTHER);
        }

        $string = $request->query->get('search');

        $pagination = $paginator->paginate(
            $userRepository->findByString($string),
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('@backend/crud/index.html.twig', [
            'config' => ['entity' => 'user', 'icon' => 'people-fill'],
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'backend_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $userRepository->save($user, true);

            return $this->redirectToRoute('backend_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@backend/crud/new.html.twig', [
            'config' => ['entity' => 'user', 'icon' => 'people-fill'],
            'entity' => $user,
            'form' => $form,
            'isNew' => true
        ]);
    }

    #[Route('/edit/{id}', name: 'backend_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            if ($form->get('plainPassword')->getData()) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }

            $userRepository->save($user, true);

            return $this->redirectToRoute('backend_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@backend/crud/edit.html.twig', [
            'config' => ['entity' => 'user', 'icon' => 'people-fill'],
            'entity' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'backend_user_delete', methods: ['POST'])]
    #[IsGranted('MANAGE_SELF', 'user')]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('backend_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
