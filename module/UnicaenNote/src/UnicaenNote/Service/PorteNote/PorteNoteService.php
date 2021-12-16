<?php

namespace UnicaenNote\Service\PorteNote;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenNote\Entity\Db\PorteNote;
use Zend\Mvc\Controller\AbstractActionController;

class PorteNoteService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param PorteNote $portenote
     * @return PorteNote
     */
    public function create(PorteNote $portenote) : PorteNote
    {
        try {
            $this->getEntityManager()->persist($portenote);
            $this->getEntityManager()->flush($portenote);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $portenote;
    }

    /**
     * @param PorteNote $portenote
     * @return PorteNote
     */
    public function update(PorteNote $portenote) : PorteNote
    {
        try {
            $this->getEntityManager()->flush($portenote);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $portenote;
    }

    /**
     * @param PorteNote $portenote
     * @return PorteNote
     */
    public function historise(PorteNote $portenote) : PorteNote
    {
        try {
            $portenote->historiser();
            $this->getEntityManager()->flush($portenote);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $portenote;
    }

    /**
     * @param PorteNote $portenote
     * @return PorteNote
     */
    public function restore(PorteNote $portenote) : PorteNote
    {
        try {
            $portenote->dehistoriser();
            $this->getEntityManager()->flush($portenote);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $portenote;
    }

    /**
     * @param PorteNote $portenote
     * @return PorteNote
     */
    public function delete(PorteNote $portenote) : PorteNote
    {
        try {
            $this->getEntityManager()->remove($portenote);
            $this->getEntityManager()->flush($portenote);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $portenote;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(PorteNote::class)->createQueryBuilder('portenote')
            ->addSelect('note')->leftjoin('portenote.notes', 'note')
            ->addSelect('ntype')->leftjoin('note.type', 'ntype');
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return PorteNote[]
     */
    public function getPortesNotes(string $champ='id', string  $ordre='ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('portenote.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getPortesNotesAsOptions(string $champ='id', string  $ordre='ASC') : array
    {
        $portesnotes = $this->getPortesNotes($champ, $ordre);
        $array = [];
        foreach ($portesnotes as $portenote) {
            $array[$portenote->getId()] = "#" .$portenote->getId() . " - TODO " ;
        }
        return $array;
    }

    /**
     * @param int|null $id
     * @return PorteNote|null
     */
    public function getPorteNote(?int $id) : ?PorteNote
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('portenote.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs PorteNote partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return PorteNote|null
     */
    public function getRequestePorteNote(AbstractActionController $controller, $param='porte-note') : ?PorteNote
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getPorteNote($id);
        return $result;
    }

    /**
     * @param string $accroche
     * @return PorteNote
     */
    public function getPorteNoteByAccroche(string $accroche) : ?PorteNote
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('portenote.accroche = :accroche')
            ->setParameter('accroche', $accroche)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs PorteNote partagent la même accroche [".$accroche."]");
        }
        return $result;
    }

}