<?php

namespace Element\Service\CompetenceType;

use Element\Entity\Db\CompetenceType;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class CompetenceTypeService
{
    use EntityManagerAwareTrait;

    /** ENTITY MANAGMENT **********************************************************************************************/

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function create(CompetenceType $type) : CompetenceType
    {
        try {
            $this->getEntityManager()->persist($type);
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function update(CompetenceType $type) : CompetenceType
    {
        try {
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function historise(CompetenceType $type) : CompetenceType
    {
        try {
            $type->historiser();
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function restore(CompetenceType $type) : CompetenceType
    {
        try {
            $type->dehistoriser();
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param CompetenceType $type
     * @return CompetenceType
     */
    public function delete(CompetenceType $type) : CompetenceType
    {
        try {
            $this->getEntityManager()->remove($type);
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /** REQUETE *******************************************************************************************************/

    /**
     * @param string $champ
     * @param string $order
     * @return CompetenceType[]
     */
    public function getCompetencesTypes(string $champ = 'libelle', string $order = 'ASC') : array
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
    public function getCompetencesTypesAsOptions(string $champ = 'libelle', string $order = 'ASC') : array
    {
        $types = $this->getCompetencesTypes($champ, $order);
        $options = [];
        foreach ($types as $type) {
            $options[$type->getId()] = $type->getLibelle();
        }
        return $options;
    }

    /**
     * @param int|null $id
     * @return CompetenceType
     */
    public function getCompetenceType(?int $id) : ?CompetenceType
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
    public function getRequestedCompetenceType(AbstractActionController $controller, string $paramName = 'competence-type') : ?CompetenceType
    {
        $id = $controller->params()->fromRoute($paramName);
        $type = $this->getCompetenceType($id);
        return $type;
    }
}
