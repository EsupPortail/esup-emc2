<?php

namespace UnicaenNote\Service\PorteNote;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenNote\Entity\Db\PorteNote;
use UnicaenUtilisateur\Service\GestionEntiteHistorisationTrait;
use Zend\Mvc\Controller\AbstractActionController;

class PorteNoteService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param PorteNote $portenote
     * @return PorteNote
     */
    public function create(PorteNote $portenote)
    {
        $this->createFromTrait($portenote);
        return $portenote;
    }

    /**
     * @param PorteNote $portenote
     * @return PorteNote
     */
    public function update(PorteNote $portenote)
    {
        $this->updateFromTrait($portenote);
        return $portenote;
    }

    /**
     * @param PorteNote $portenote
     * @return PorteNote
     */
    public function historise(PorteNote $portenote)
    {
        $this->historiserFromTrait($portenote);
        return $portenote;
    }

    /**
     * @param PorteNote $portenote
     * @return PorteNote
     */
    public function restore(PorteNote $portenote)
    {
        $this->restoreFromTrait($portenote);
        return $portenote;
    }

    /**
     * @param PorteNote $portenote
     * @return PorteNote
     */
    public function delete(PorteNote $portenote)
    {
        $this->deleteFromTrait($portenote);
        return $portenote;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
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
    public function getPortesNotes(string $champ='id', string  $ordre='ASC')
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
    public function getPortesNotesAsOptions(string $champ='id', string  $ordre='ASC')
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
     * @return PorteNote
     */
    public function getPorteNote(?int $id)
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
     * @return PorteNote
     */
    public function getRequestePorteNote(AbstractActionController $controller, $param='porte-note')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getPorteNote($id);
        return $result;
    }

    /**
     * @param string $accroche
     * @return PorteNote
     */
    public function getPorteNoteByAccroche(string $accroche)
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