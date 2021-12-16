<?php

namespace UnicaenNote\Service\Note;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenNote\Entity\Db\Note;
use UnicaenNote\Entity\Db\PorteNote;
use Application\Service\GestionEntiteHistorisationTrait;
use Zend\Mvc\Controller\AbstractActionController;

class NoteService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Note $note
     * @return Note
     */
    public function create(Note $note)
    {
        $this->createFromTrait($note);
        return $note;
    }

    /**
     * @param Note $note
     * @return Note
     */
    public function update(Note $note)
    {
        $this->updateFromTrait($note);
        return $note;
    }

    /**
     * @param Note $note
     * @return Note
     */
    public function historise(Note $note)
    {
        $this->historiserFromTrait($note);
        return $note;
    }

    /**
     * @param Note $note
     * @return Note
     */
    public function restore(Note $note)
    {
        $this->restoreFromTrait($note);
        return $note;
    }

    /**
     * @param Note $note
     * @return Note
     */
    public function delete(Note $note)
    {
        $this->deleteFromTrait($note);
        return $note;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(Note::class)->createQueryBuilder('note')
            ->addSelect('ntype')->leftjoin('note.type', 'ntype')
            ->addSelect('portenote')->join('note.portenote', 'portenote')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Note[]
     */
    public function getNotes(string $champ='id', string $ordre='ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('note.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $id
     * @return Note
     */
    public function getNote(int $id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('note.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Note partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Note
     */
    public function getRequestedNote(AbstractActionController $controller, string $param='note')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getNote($id);
        return $result;
    }

    /**
     * @param PorteNote $portenote
     * @return Note[]
     */
    public function getNotesByPorteNote(PorteNote $portenote)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('note.portenote = :portenote')
            ->setParameter('portenote', $portenote)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}