<?php

namespace Formation\Service\Formateur;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\Formateur;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class FormateurService
{
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Formateur $formateur
     * @return Formateur
     */
    public function create(Formateur $formateur) : Formateur
    {
        try {
            $this->getEntityManager()->persist($formateur);
            $this->getEntityManager()->flush($formateur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $formateur;
    }

    /**
     * @param Formateur $formateur
     * @return Formateur
     */
    public function update(Formateur $formateur) : Formateur
    {
        try {
            $this->getEntityManager()->flush($formateur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $formateur;
    }

    /**
     * @param Formateur $formateur
     * @return Formateur
     */
    public function historise(Formateur $formateur) : Formateur
    {
        try {
            $formateur->historiser();
            $this->getEntityManager()->flush($formateur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $formateur;
    }

    /**
     * @param Formateur $formateur
     * @return Formateur
     */
    public function restore(Formateur $formateur) : Formateur
    {
        try {
            $formateur->dehistoriser();
            $this->getEntityManager()->flush($formateur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $formateur;
    }

    /**
     * @param Formateur $formateur
     * @return Formateur
     */
    public function delete(Formateur $formateur) : Formateur
    {
        try {
            $this->getEntityManager()->remove($formateur);
            $this->getEntityManager()->flush($formateur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $formateur;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Formateur::class)->createQueryBuilder('formateur')
            ->addSelect('finstance')->join('formateur.instance', 'finstance');
        return $qb;
    }

    /**
     * @param int|null $id
     * @return Formateur|null
     */
    public function getFormateur(?int $id) : ?Formateur
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formateur.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Formateur partagent le même id [" . $id . "]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Formateur|null
     */
    public function getRequestedFormateur(AbstractActionController $controller, string $param = 'formateur') : ?Formateur
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getFormateur($id);
    }
}