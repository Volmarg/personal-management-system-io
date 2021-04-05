<?php


namespace App\Action\Vue;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VueAction extends AbstractController
{
    const TEMPLATE_BASE = "base.html.twig";

    /**
     * This action is needed to allow vue calling pages via standard url calls,
     * the only returned thing here is a response - to prevent symfony from crashing when vue switches pages
     */
    #[Route("/module/notes/category/{id}", name: "modules_notes_category", methods: ["GET"])]
    public function handleVueCallForPageDisplay(): Response
    {
        return $this->render(self::TEMPLATE_BASE);
    }

}