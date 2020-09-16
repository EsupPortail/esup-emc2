<?php

namespace Application\Service\MetierReference;

use Application\Entity\Db\MetierReference;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class MetierReferenceService
{
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param MetierReference $referentiel
     * @return MetierReference
     */
    public function create(MetierReference $referentiel)
    {
        $this->createFromTrait($referentiel);
        return $referentiel;
    }

    /**
     * @param MetierReference $referentiel
     * @return MetierReference
     */
    public function update(MetierReference $referentiel)
    {
        $this->updateFromTrait($referentiel);
        return $referentiel;
    }

    /**
     * @param MetierReference $referentiel
     * @return MetierReference
     */
    public function historise(MetierReference $referentiel)
    {
        $this->historiserFromTrait($referentiel);
        return $referentiel;
    }

    /**
     * @param MetierReference $referentiel
     * @return MetierReference
     */
    public function restore(MetierReference $referentiel)
    {
        $this->restoreFromTrait($referentiel);
        return $referentiel;
    }

    /**
     * @param MetierReference $referentiel
     * @return MetierReference
     */
    public function delete(MetierReference $referentiel)
    {
        $this->deleteFromTrait($referentiel);
        return $referentiel;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(MetierReference::class)->createQueryBuilder('reference')
            ->addSelect('metier')->join('reference.metier', 'metier')
            ->addSelect('categorie')->leftJoin('metier.categorie', 'categorie')
            ->addSelect('referentiel')->join('reference.referentiel', 'referentiel');

        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return MetierReference[]
     */
    public function getMetiersReferences($champ = 'code', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('reference.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getMetiersReferencesAsOptions()
    {
        $references = $this->getMetiersReferences();
        $array = [];

        foreach ($references as $reference) {
            $array[$reference->getId()] = $reference->getReferentiel()->getLibelleCourt() . " - " . $reference->getCode();
        }

        return $array;
    }

    /**
     * @param integer $id
     * @return MetierReference
     */
    public function getMetierReference(int $id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('reference.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs MetierReference partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return MetierReference
     */
    public function getRequestedMetierReference(AbstractActionController $controller, string $param = "reference")
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getMetierReference($id);

        return $result;
    }

}