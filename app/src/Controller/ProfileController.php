<?php
/**
 * Profile controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\ChangeProfileType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ProfileController.
 *
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * View action.
     *
     * @param User $user User entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="profile_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     * @Entity("users", expr="repository.find(id)")
     */
    public function view(User $user): Response
    {
        if ('0' === $user->getIsActive()) {
            $this->addFlash('warning', 'message.item_not_found');

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'profile/view.html.twig',
            [
                'user' => $user,
                'roles' => $user->getRoles(),
                'loggedUser' => $this->getUser(),
            ]
        );
    }

    /**
     * Change password action.
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepository               $repository
     * @param User id                      $id
     *
     * @Route(
     *     "/{id}/change-password",
     *     name="profile_change_password",
     *     requirements={"id": "[1-9]\d*"},
     * )
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $repository, $id): Response
    {
        $user = $this->getUser();
        $userProfile = $repository->find($id);

        if ($userProfile !== $this->getUser()) {
            $this->addFlash('warning', 'message.forbidden');

            return $this->redirectToRoute('profile_view', ['id' => $user->getId()]);
        }

        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $user->getNewPassword()
                )
            );
            try {
                $repository->save($user);
            } catch (\Exception $e) {
                error_log($e->getMessage());
            }
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('profile_view', ['id' => $user->getId()]);
        }

        return $this->render('profile/change-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * changeProfile action.
     *
     * @param Request        $request    HTTP request
     * @param User           $user
     * @param UserRepository $repository Category repository
     *
     * @return Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/change-profile",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="profile_change",
     * )
     */
    public function changeProfile(Request $request, User $user, UserRepository $repository): Response
    {
        if ($user !== $this->getUser()) {
            $this->addFlash('warning', 'message.forbidden');

            return $this->redirectToRoute('profile_view', ['id' => $user->getId()]);
        }
        $form = $this->createForm(ChangeProfileType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTime());
            $repository->save($user);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('profile_view', ['id' => $user->getId()]);
        }

        return $this->render(
            'profile/change-profile.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
