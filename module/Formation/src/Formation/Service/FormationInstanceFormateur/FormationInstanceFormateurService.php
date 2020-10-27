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
    public function create(FormationInstanceFormateur $formateur)
    {
        $this->createFromTrait($formateur);
        return $formateur;
    }

    /**
     * @param FormationInstanceFormateur $formateur
     * @return FormationInstanceFormateur
     */
    public function update(FormationInstanceFormateur $formateur)
    {
        $this->updateFromTrait($formateur);
        return $formateur;
    }

    /**
     * @param FormationInstanceFormateur $formateur
     * @return FormationInstanceFormateur
     */
    public function historise(FormationInstanceFormateur $formateur)
    {
        $this->historiserFromTrait($formateur);
        return $formateur;
    }

    /**
     * @param FormationInstanceFormateur $formateur
     * @return FormationInstanceFormateur
     */
    public function restore(FormationInstanceFormateur $formateur)
    {
        $this->restoreFromTrait($formateur);
        return $formateur;
    }

    /**
     * @param FormationInstanceFormateur $formateur
     * @return FormationInstanceFormateur
     */
    public function delete(FormationInstanceFormateur $formateur)
    {
        $this->deleteFromTrait($formateur);
        return $formateur;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(FormationInstanceFormateur::class)->createQueryBuilder('formateur')
            ->addSelect('finstance')->join('formateur.instance', 'finstance');
        return $qb;
    }

    /**
     * @param integer $id
     * @return FormationInstanceFormateur
     */
    public function getFormationInstanceFormateur(int $id)
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
     * @return FormationInstanceFormateur
     */
    public function getRequestedFormationInstanceFormateur(AbstractActionController $controller, string $param = 'formateur')
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getFormationInstanceFormateur($id);
    }
}