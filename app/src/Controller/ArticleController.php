<?php
/**
 * Article controller.
 */

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
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
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * Index action.
     *
     * @param Request            $request    HTTP request
     * @param ArticleRepository  $repository Repository
     * @param PaginatorInterface $paginator  Paginator
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="article_index",
     * )
     */
    public function index(Request $request, ArticleRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Article::NUMBER_OF_ITEMS
        );

        return $this->render(
            'article/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * View action.
     *
     * @param Article $article Article entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="article_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     * @Entity("articles", expr="repository.find(id)")
     */
    public function view(Article $article): Response
    {
        return $this->render(
            'article/view.html.twig',
            ['article' => $article]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request           HTTP request
     * @param \App\Repository\CommentRepository         $commentRepository Comment repository
     * @param ArticleRepository                         $articleRepository Article repository
     * @param                                           $id                Id of article element
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/add-comment",
     *     requirements={"id": "[1-9]\d*"},
     *     methods={"GET", "POST"},
     *     name="article_comment_new",
     * )
     */
    public function addComment(Request $request, CommentRepository $commentRepository, ArticleRepository $articleRepository, $id): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        $article = $articleRepository->find($id);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime());
            $comment->setUpdatedAt(new \DateTime());
            $comment->setIsVisible(true);
            $comment->setArticle($article);
            $comment->setAuthor(null);
            $commentRepository->save($comment);

            return $this->redirectToRoute('article_view', ['id' => $comment->getArticle()->getId()]);
        }

        return $this->render(
            'article/add-comment.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
                'article' => $article,
            ]
        );
    }
}
