<?php

namespace UnicaenNote\Service\Note;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenNote\Entity\Db\Note;
use UnicaenNote\Entity\Db\PorteNote;
use Zend\Mvc\Controller\AbstractActionController;

class NoteService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Note $note
     * @return Note
     */
    public function create(Note $note) : Note
    {
        try {
            $this->getEntityManager()->persist($note);
            $this->getEntityManager()->flush($note);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $note;
    }

    /**
     * @param Note $note
     * @return Note
     */
    public function update(Note $note) : Note
    {
        try {
            $this->getEntityManager()->flush($note);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $note;
    }

    /**
     * @param Note $note
     * @return Note
     */
    public function historise(Note $note) : Note
    {
        try {
            $note->historiser();
            $this->getEntityManager()->flush($note);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $note;
    }

    /**
     * @param Note $note
     * @return Note
     */
    public function restore(Note $note) : Note
    {
        try {
            $note->dehistoriser();
            $this->getEntityManager()->flush($note);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $note;
    }

    /**
     * @param Note $note
     * @return Note
     */
    public function delete(Note $note) : Note
    {
        try {
            $this->getEntityManager()->remove($note);
            $this->getEntityManager()->flush($note);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $note;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
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
    public function getNotes(string $champ='id', string $ordre='ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('note.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int|null $id
     * @return Note|null
     */
    public function getNote(?int $id) : ?Note
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
     * @return Note|null
     */
    public function getRequestedNote(AbstractActionController $controller, string $param='note') : ?Note
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getNote($id);
        return $result;
    }

    /**
     * @param PorteNote $portenote
     * @return Note[]
     */
    public function getNotesByPorteNote(PorteNote $portenote) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('note.portenote = :portenote')
            ->setParameter('portenote', $portenote)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}