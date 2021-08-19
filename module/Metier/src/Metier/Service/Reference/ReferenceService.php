<?php

namespace Metier\Service\Reference;

use Metier\Entity\Db\Reference;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class ReferenceService
{
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Reference $referentiel
     * @return Reference
     */
    public function create(Reference $referentiel) : Reference
    {
        $this->createFromTrait($referentiel);
        return $referentiel;
    }

    /**
     * @param Reference $referentiel
     * @return Reference
     */
    public function update(Reference $referentiel) : Reference
    {
        $this->updateFromTrait($referentiel);
        return $referentiel;
    }

    /**
     * @param Reference $referentiel
     * @return Reference
     */
    public function historise(Reference $referentiel) : Reference
    {
        $this->historiserFromTrait($referentiel);
        return $referentiel;
    }

    /**
     * @param Reference $referentiel
     * @return Reference
     */
    public function restore(Reference $referentiel) : Reference
    {
        $this->restoreFromTrait($referentiel);
        return $referentiel;
    }

    /**
     * @param Reference $referentiel
     * @return Reference
     */
    public function delete(Reference $referentiel) : Reference
    {
        $this->deleteFromTrait($referentiel);
        return $referentiel;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Reference::class)->createQueryBuilder('reference')
            ->addSelect('metier')->join('reference.metier', 'metier')
            ->addSelect('categorie')->leftJoin('metier.categorie', 'categorie')
            ->addSelect('referentiel')->join('reference.referentiel', 'referentiel');

        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Reference[]
     */
    public function getReferences(string $champ = 'code', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('reference.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getReferencesAsOptions() : array
    {
        $references = $this->getReferences();
        $array = [];

        foreach ($references as $reference) {
            $array[$reference->getId()] = $reference->getReferentiel()->getLibelleCourt() . " - " . $reference->getCode();
        }

        return $array;
    }

    /**
     * @param integer $id
     * @return Reference
     */
    public function getReference(int $id) : Reference
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('reference.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Reference partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Reference
     */
    public function getRequestedReference(AbstractActionController $controller, string $param = "reference") : Reference
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getReference($id);

        return $result;
    }

}