<?php

namespace Formation\Service\FormationElement;

use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationElement;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use phpDocumentor\Reflection\Types\Array_;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class FormationElementService {
    use GestionEntiteHistorisationTrait;
    
    /** Gestion des entites ***************************************************************************************/

    /**
     * @param FormationElement $element
     * @return FormationElement
     */
    public function create(FormationElement $element)
    {
        $this->createFromTrait($element);
        return $element;
    }

    /**
     * @param FormationElement $element
     * @return FormationElement
     */
    public function update(FormationElement $element)
    {
        $this->updateFromTrait($element);
        return $element;
    }

    /**
     * @param FormationElement $element
     * @return FormationElement
     */
    public function restore(FormationElement $element)
    {
        $this->restoreFromTrait($element);
        return $element;
    }

    /**
     * @param FormationElement $element
     * @return FormationElement
     */
    public function historise(FormationElement $element)
    {
        $this->historiserFromTrait($element);
        return $element;
    }

    /**
     * @param FormationElement $element
     * @return FormationElement
     */
    public function delete(FormationElement $element)
    {
        $this->deleteFromTrait($element);
        return $element;
    }

    /** REQUETAGE  ****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(FormationElement::class)->createQueryBuilder('formationelement')
            ->addSelect('formation')->join('formationelement.formation', 'formation')
        ;
        return $qb;
    }

    /**
     * @param int $id
     * @return FormationElement
     */
    public function getFormationElement(int $id) : FormationElement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formationelement.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationElement partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return FormationElement
     */
    public function getRequestedFormationElement(AbstractActionController $controller, string $param = "formation-element")
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getFormationElement($id);
    }

    /**
     * @param Formation $formation
     * @return FormationElement[]
     */
    public function getElementsByFormation (Formation $formation) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formationelement.formation = :formation')
            ->setParameter('formation', $formation)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

}