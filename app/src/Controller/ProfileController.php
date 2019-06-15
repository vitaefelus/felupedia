<?php
/**
 * Profile controller.
 */

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

/**
 * Class ArticleController.
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

}
