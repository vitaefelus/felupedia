<?php
/**
 * Article controller.
 */

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Paragraph;
use App\Form\AddArticleType;
use App\Form\ParagraphType;
use App\Repository\ArticleRepository;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\ParagraphRepository;
use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
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
        $query = $repository->queryAccepted();

        if ($request->query->getAlnum('filter')) {
            $query = $repository->queryFilter($request);
        }

        $pagination = $paginator->paginate(
            $query,
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
        if ('0' === $article->getIsAccepted()) {
            $this->addFlash('warning', 'message.item_not_found');

            return $this->redirectToRoute('article_index');
        }

        return $this->render(
            'article/view.html.twig',
            ['article' => $article]
        );
    }

    /**
     * New Article action.
     *
     * @param Request           $request    HTTP request
     * @param ArticleRepository $repository Article repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="article_new",
     * )
     */
    public function new(Request $request, ArticleRepository $repository): Response
    {
        $article = new Article();

        $form = $this->createForm(AddArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new DateTime());
            $article->setUpdatedAt(new DateTime());
            $article->setIsAccepted(false);
            $article->setIsVisible(false);
            $article->setAuthor($this->getUser());
            $repository->save($article);

            return $this->redirectToRoute('article_view', ['id' => $article->getId()]);
        }

        return $this->render(
            'article/new.html.twig',
            [
                'form' => $form->createView(),
                'article' => $article,
            ]
        );
    }

    /**
     * Add Paragraph action.
     *
     * @param Request             $request             HTTP request
     * @param ParagraphRepository $paragraphRepository Paragraph repository
     * @param ArticleRepository   $articleRepository   Article repository
     * @param                     $id
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/add-paragraph",
     *     requirements={"id": "[1-9]\d*"},
     *     methods={"GET", "POST"},
     *     name="article_add_paragraph",
     * )
     */
    public function addParagraph(Request $request, ParagraphRepository $paragraphRepository, ArticleRepository $articleRepository, $id): Response
    {
        $article = $articleRepository->find($id);

        if ($article->getAuthor() !== $this->getUser()) {
            $this->addFlash('warning', 'message.item_not_found');

            return $this->redirectToRoute('article_view', ['id' => $id]);
        }

        $paragraph = new Paragraph();
        $form = $this->createForm(ParagraphType::class, $paragraph);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paragraph->setArticle($article);
            $paragraphRepository->save($paragraph);

            return $this->redirectToRoute('article_view', ['id' => $id]);
        }

        return $this->render(
            'article/add-paragraph.html.twig',
            [
                'form' => $form->createView(),
                'article' => $article,
            ]
        );
    }

    /**
     * Edit Paragraph action.
     *
     * @param Request $request    HTTP request
     * @param Paragraph                                 $paragraph
     * @param ParagraphRepository                       $repository Paragraph repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/{paragraph}/edit",
     *     methods={"GET", "POST", "PUT"},
     *     requirements={
     *        "id": "[1-9]\d*",
     *        "paragraph": "[1-9]\d*"
     *     },
     *     name="article_edit_paragraph",
     * )
     */
    public function editParagraph(Request $request, Paragraph $paragraph, ParagraphRepository $repository): Response
    {
        if ($paragraph->getArticle()->getAuthor() !== $this->getUser()) {
            $this->addFlash('warning', 'message.forbidden');

            return $this->redirectToRoute('article_view', ['id' => $paragraph->getArticle()->getId()]);
        }
        $form = $this->createForm(ParagraphType::class, $paragraph, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($paragraph);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('article_view', ['id' => $paragraph->getArticle()->getId()]);
        }

        return $this->render(
            'article/edit-paragraph.html.twig',
            [
                'form' => $form->createView(),
                'paragraph' => $paragraph,
                'article' => $paragraph->getArticle(),
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request             $request    HTTP request
     * @param Paragraph           $paragraph  Paragraph entity
     * @param ParagraphRepository $repository Paragraph repository
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/{paragraph}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={
     *        "id": "[1-9]\d*",
     *        "paragraph": "[1-9]\d*"
     *     },
     *     name="article_delete_paragraph",
     * )
     */
    public function delete(Request $request, Paragraph $paragraph, ParagraphRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $paragraph, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($paragraph);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('article_view', ['id' => $paragraph->getArticle()->getId()]);
        }

        return $this->render(
            'article/delete-paragraph.html.twig',
            [
                'form' => $form->createView(),
                'paragraph' => $paragraph,
                'article' => $paragraph->getArticle(),
            ]
        );
    }

    /**
     * Add comment action.
     *
     * @param Request           $request           HTTP request
     * @param CommentRepository $commentRepository Comment repository
     * @param ArticleRepository $articleRepository Article repository
     * @param id                $id                Id of article element
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
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
            $comment->setCreatedAt(new DateTime());
            $comment->setUpdatedAt(new DateTime());
            $comment->setIsVisible(true);
            $comment->setArticle($article);
            $comment->setAuthor($this->getUser());
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
