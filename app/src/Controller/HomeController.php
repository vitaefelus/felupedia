<?php
/**
 * Home controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $user = $this->getUser();

        return $this->render(
            'home/index.html.twig',
            [
                'user' => $user,
            ]
        );
    }
}
