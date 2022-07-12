<?php

namespace Formation\Service\FormationInstanceFormateur;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\FormationInstanceFormateur;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class FormationInstanceFormateurService
{
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FormationInstanceFormateur $formateur
     * @return FormationInstanceFormateur
     */
    public function create(FormationInstanceFormateur $formateur) : FormationInstanceFormateur
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
     * @param FormationInstanceFormateur $formateur
     * @return FormationInstanceFormateur
     */
    public function update(FormationInstanceFormateur $formateur) : FormationInstanceFormateur
    {
        try {
            $this->getEntityManager()->flush($formateur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $formateur;
    }

    /**
     * @param FormationInstanceFormateur $formateur
     * @return FormationInstanceFormateur
     */
    public function historise(FormationInstanceFormateur $formateur) : FormationInstanceFormateur
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
     * @param FormationInstanceFormateur $formateur
     * @return FormationInstanceFormateur
     */
    public function restore(FormationInstanceFormateur $formateur) : FormationInstanceFormateur
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
     * @param FormationInstanceFormateur $formateur
     * @return FormationInstanceFormateur
     */
    public function delete(FormationInstanceFormateur $formateur) : FormationInstanceFormateur
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
        $qb = $this->getEntityManager()->getRepository(FormationInstanceFormateur::class)->createQueryBuilder('formateur')
            ->addSelect('finstance')->join('formateur.instance', 'finstance');
        return $qb;
    }

    /**
     * @param int|null $id
     * @return FormationInstanceFormateur|null
     */
    public function getFormationInstanceFormateur(?int $id) : ?FormationInstanceFormateur
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formateur.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationInstanceFormateur partagent le même id [" . $id . "]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return FormationInstanceFormateur|null
     */
    public function getRequestedFormationInstanceFormateur(AbstractActionController $controller, string $param = 'formateur') : ?FormationInstanceFormateur
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getFormationInstanceFormateur($id);
    }
}