<?php

namespace UnicaenEtat\Service\EtatType;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenEtat\Entity\Db\EtatType;
use Laminas\Mvc\Controller\AbstractActionController;

class EtatTypeService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES  ******************************************************************************************/

    /**
     * @param EtatType $type
     * @return EtatType
     */
    public function create(EtatType $type) : EtatType
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
     * @param EtatType $type
     * @return EtatType
     */
    public function update(EtatType $type) : EtatType
    {
        try {
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param EtatType $type
     * @return EtatType
     */
    public function historise(EtatType $type) : EtatType
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
     * @param EtatType $type
     * @return EtatType
     */
    public function restore(EtatType $type) : EtatType
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
     * @param EtatType $type
     * @return EtatType
     */
    public function delete(EtatType $type) : EtatType
    {
        try {
            $this->getEntityManager()->remove($type);
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(EtatType::class)->createQueryBuilder('etype')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return EtatType[] @desc [code => etat]
     */
    public function getEtatTypes(string $champ = 'code', string $ordre='ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('etype.'.$champ, $ordre)
        ;
        $result =  $qb->getQuery()->getResult();

        $dictionnaire = [];
        foreach ($result as $etat) {
            $dictionnaire[$etat->getCode()] = $etat;
        }
        return $dictionnaire;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getEtatTypesAsOptions($champ = 'code', $ordre = 'ASC')
    {
        $result = $this->getEtatTypes($champ,$ordre);
        $array = [];
        foreach ($result as $item) {
            $array[$item->getId()] = $item->getLibelle();
        }
        return $array;
    }

    /**
     * @param int $id
     * @return EtatType
     */
    public function getEtatType(int $id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etype.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EtatType partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return EtatType
     */
    public function getRequestedEtatType(AbstractActionController $controller, $param='etat-type')
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getEtatType($id);
    }

    /**
     * @param string $code
     * @return EtatType|null
     */
    public function getEtatTypeByCode(string $code) : ?EtatType
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etype.code = :code')
            ->setParameter('code', $code)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EtatType partagent le même code [".$code."]");
        }
        return $result;
    }
}