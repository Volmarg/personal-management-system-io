<?php


namespace App\Controller\Modules\Notes;


use App\Entity\Modules\Notes\MyNote;
use App\Repository\Modules\Notes\NoteRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NotesController extends AbstractController
{
    /**
     * @var NoteRepository $noteRepository
     */
    private NoteRepository $noteRepository;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    /**
     * Will save the new entity or update the state of already existing one
     *
     * @param MyNote $myNote
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(MyNote $myNote): void
    {
        $this->noteRepository->save($myNote);
    }

    /**
     * Will remove all entries from DB
     * @throws Exception
     */
    public function removeAll(): void
    {
        $this->noteRepository->removeAll();
    }

}