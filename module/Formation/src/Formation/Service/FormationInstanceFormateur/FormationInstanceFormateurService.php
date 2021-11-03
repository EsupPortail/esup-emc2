<?php

namespace Formation\Service\FormationInstanceFormateur;

use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\FormationInstanceFormateur;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class FormationInstanceFormateurService
{
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FormationInstanceFormateur $formateur
     * @return FormationInstanceFormateur
     */
    public function create(FormationInstanceFormateur $formateur) : FormationInstanceFormateur
    {
        $this->createFromTrait($formateur);
        return $formateur;
    }

    /**
     * @param FormationInstanceFormateur $formateur
     * @return FormationInstanceFormateur
     */
    public function update(FormationInstanceFormateur $formateur) : FormationInstanceFormateur
    {
        $this->updateFromTrait($formateur);
        return $formateur;
    }

    /**
     * @param FormationInstanceFormateur $formateur
     * @return FormationInstanceFormateur
     */
    public function historise(FormationInstanceFormateur $formateur) : FormationInstanceFormateur
    {
        $this->historiserFromTrait($formateur);
        return $formateur;
    }

    /**
     * @param FormationInstanceFormateur $formateur
     * @return FormationInstanceFormateur
     */
    public function restore(FormationInstanceFormateur $formateur) : FormationInstanceFormateur
    {
        $this->restoreFromTrait($formateur);
        return $formateur;
    }

    /**
     * @param FormationInstanceFormateur $formateur
     * @return FormationInstanceFormateur
     */
    public function delete(FormationInstanceFormateur $formateur) : FormationInstanceFormateur
    {
        $this->deleteFromTrait($formateur);
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