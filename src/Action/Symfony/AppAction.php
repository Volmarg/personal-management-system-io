<?php


namespace App\Action\Symfony;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Variety of actions not associated with the modules but the symfony itself
 *
 * Class AppAction
 * @package App\Action\Symfony
 */
class AppAction extends AbstractController
{

    #[Route("/", name: "home_page", methods: [Request::METHOD_GET])]
    public function homePage(): RedirectResponse
    {
        return new RedirectResponse($this->generateUrl("modules_dashboard_overview"));
    }

}