<?php

namespace App\Action\Module\Notes;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotesAction extends AbstractController
{

    #[Route("/testing", name: "testing", methods: ["GET"])]
    public function testing(): Response
    {
        return $this->render("base.html.twig");
    }

}