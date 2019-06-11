<?php
/**
 * Admin controller.
 */

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController.
 *
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * Index action.
     *
     * @return Response
     *
     * @Route(
     *     "/",
     *     name="admin_index",
     * )
     */
    public function index(): Response
    {
        return $this->render(
            'admin/index.html.twig'
        );
    }

    /**
     * List Users action.
     *
     * @param Request            $request    HTTP request
     * @param UserRepository     $repository Repository
     * @param PaginatorInterface $paginator  Paginator
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/user",
     *     name="admin_list_users",
     * )
     */
    public function listUsers(Request $request, UserRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            User::NUMBER_OF_ITEMS
        );

        return $this->render(
            'admin/user.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * listArticles action.
     *
     * @param Request            $request    HTTP request
     * @param ArticleRepository  $repository Repository
     * @param PaginatorInterface $paginator  Paginator
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/article",
     *     name="admin_list_articles",
     * )
     */
    public function listArticles(Request $request, ArticleRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 5)
        );

        return $this->render(
            'admin/article.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * listNotAcceptedArticles action.
     *
     * @param Request            $request    HTTP request
     * @param ArticleRepository  $repository Repository
     * @param PaginatorInterface $paginator  Paginator
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/article/not-accepted",
     *     name="admin_list_not_accepted_articles",
     * )
     */
    public function listNotAcceptedArticles(Request $request, ArticleRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->findBy(['isAccepted' => false]),
            $request->query->getInt('page', 1),
            Article::NUMBER_OF_ITEMS
        );

        return $this->render(
            'admin/article.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Accept article action.
     *
     * @param Request           $request
     * @param Article           $article
     * @param ArticleRepository $repository
     *
     * @return Response
     *
     * @throws \Exception
     *
     * @Route(
     *     "/article/{id}/accept",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="admin_accept_article"
     * )
     */
    public function acceptArticle(Request $request, Article $article, ArticleRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $article, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt(new \DateTime());
            $article->setIsAccepted(true);
            $repository->save($article);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('admin_list_articles');
        }

        return $this->render(
            'admin/article-accept.html.twig',
            [
                'form' => $form->createView(),
                'article' => $article,
            ]
        );
    }
}
