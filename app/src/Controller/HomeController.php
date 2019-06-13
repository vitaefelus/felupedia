<?php
/**
 * Home controller.
 */

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController.
 *
 * @Route("/")
 */
class HomeController extends AbstractController
{
    /**
     * Index action.
     *
     * @return Response
     *
     * @Route(
     *     "/",
     *     name="home",
     * )
     */
    public function index(): Response
    {
        return $this->render(
            'home/index.html.twig'
        );
    }
}
