<?php

namespace Application\Service\CompetenceType;

use Application\Entity\Db\CompetenceType;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class CompetenceTypeService
{
    use GestionEntiteHistorisationTrait;

    /** ENTITY MANAGMENT **********************************************************************************************/

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function create(CompetenceType $type)
    {
        $this->createFromTrait($type);
        return $type;
    }

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function update(CompetenceType $type)
    {
        $this->updateFromTrait($type);
        return $type;
    }

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function historise(CompetenceType $type)
    {
        $this->historiserFromTrait($type);
        return $type;
    }

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function restore(CompetenceType $type)
    {
        $this->restoreFromTrait($type);
        return $type;
    }

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function delete(CompetenceType $type)
    {
        $this->deleteFromTrait($type);
        return $type;
    }

    /** REQUETE *******************************************************************************************************/

    /**
     * @param string $champ
     * @param string $order
     * @return CompetenceType[]
     */
    public function getCompetencesTypes($champ = 'libelle', $order = 'ASC')
    {
        $qb = $this->getEntityManager()->getRepository(CompetenceType::class)->createQueryBuilder('type')
            ->addSelect('competence')->leftJoin('type.competences', 'competence')
            ->orderBy('type.'.$champ, $order)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $order
     * @return array
     */
    public function getCompetencesTypesAsOptions($champ = 'libelle', $order = 'ASC')
    {
        $types = $this->getCompetencesTypes($champ, $order);
        $options = [];
        foreach ($types as $type) {
            $options[$type->getId()] = $type->getLibelle();
        }
        return $options;
    }

    /**
     * @param integer $id
     * @return CompetenceType
     */
    public function getCompetenceType(int $id)
    {
        $qb = $this->getEntityManager()->getRepository(CompetenceType::class)->createQueryBuilder('type')
            ->andWhere('type.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch(NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs CompetenceType partagent le même id [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return CompetenceType
     */
    public function getRequestedCompetenceType(AbstractActionController $controller, $paramName = 'competence-type')
    {
        $id = $controller->params()->fromRoute($paramName);
        $type = $this->getCompetenceType($id);
        return $type;
    }
}
