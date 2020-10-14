<?php

namespace UnicaenEtat\Service\Etat;

use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Entity\Db\Etat;
use UnicaenEtat\Entity\Db\EtatType;
use Zend\Mvc\Controller\AbstractActionController;

class EtatService {
    use GestionEntiteHistorisationTrait;
    
    /** GESTION DES ENTITES *****************************/

    /**
     * @param Etat $etat
     * @return Etat
     */
    public function create(Etat $etat)
    {
        $this->createFromTrait($etat);
        return $etat;
    }

    /**
     * @param Etat $etat
     * @return Etat
     */
    public function update(Etat $etat)
    {
        $this->updateFromTrait($etat);
        return $etat;
    }

    /**
     * @param Etat $etat
     * @return Etat
     */
    public function historise(Etat $etat)
    {
        $this->historiserFromTrait($etat);
        return $etat;
    }

    /**
     * @param Etat $etat
     * @return Etat
     */
    public function restore(Etat $etat)
    {
        $this->restoreFromTrait($etat);
        return $etat;
    }

    /**
     * @param Etat $etat
     * @return Etat
     */
    public function delete(Etat $etat)
    {
        $this->deleteFromTrait($etat);
        return $etat;
    }

    /** REQUETAGE ***************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        //todo ajouter la jointure sur les actions une fois celles-ci en place ...
        $qb = $this->getEntityManager()->getRepository(Etat::class)->createQueryBuilder('etat')
            ->addSelect('etype')->join('etat.type', 'etype')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Etat[]
     */
    public function getEtats($champ = 'code', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('etat.' . $champ, $ordre)
        ;
        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $id
     * @return Etat
     */
    public function getEtat(int $id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etat.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Etat partagent le même id [".$id."].");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Etat
     */
    public function getRequestedEtat(AbstractActionController $controller, $param='etat')
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getEtat($id);
    }

    /**
     * @param EtatType $type
     * @return Etat[]
     */
    public function getEtatsByType(EtatType $type)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etat.type = :type')
            ->setParameter('type', $type)
        ;
        return $qb->getQuery()->getResult();
    }

}