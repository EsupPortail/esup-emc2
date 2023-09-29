<?php

namespace Element\Service\CompetenceType;

use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\QueryBuilder;
use Element\Entity\Db\CompetenceType;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

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

    public function createQueryBuilder() : QueryBuilder
    {
        try {
            $qb = $this->getEntityManager()->getRepository(CompetenceType::class)->createQueryBuilder('type')
                ->addSelect('competence')->leftJoin('type.competences', 'competence')
            ;
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problème est survenu lors de la création du QueryBuilder de [".CompetenceType::class."]",0,$e);
        }
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $order
     * @return CompetenceType[]
     */
    public function getCompetencesTypes(string $champ = 'libelle', string $order = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
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
        $qb = $this->createQueryBuilder()
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

    public function getCompetenceTypeByLibelle(string $libelle)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('type.libelle = :libelle')
            ->setParameter('libelle', $libelle)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch(NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs CompetenceType partagent le même id [".$libelle."]", $e);
        }
        return $result;
    }

    /** FACADE ***********************************************************************/

    public function createWith(?string $libelle) : CompetenceType
    {
        $type = new CompetenceType();
        $type->setLibelle($libelle);
        $this->create($type);
        return $type;
    }
}
