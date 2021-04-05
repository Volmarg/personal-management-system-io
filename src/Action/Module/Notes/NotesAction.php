<?php

namespace App\Action\Module\Notes;

use App\Attribute\Action\InternalActionAttribute;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/module/notes/", name: "module_notes_")]
class NotesAction extends AbstractController
{

    /**
     * Returns notes for category with given id
     *
     * @param string $categoryId
     * @return Response
     */
    #[Route("get-for-category/{categoryId}", name: "get_for_category", methods: ["GET"])]
    #[InternalActionAttribute]
    public function getNotesForCategory(string $categoryId): Response
    {
        // todo : add exception listener + api responses, never ever add try/catch to actions thx to this

        return new Response('todo');
    }
}