<?php

namespace Application\Service\ApplicationElement;

use Application\Entity\Db\ApplicationElement;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class ApplicationElementService {
    use GestionEntiteHistorisationTrait;
    
    /** Gestion des entites ***************************************************************************************/

    /**
     * @param ApplicationElement $element
     * @return ApplicationElement
     */
    public function create(ApplicationElement $element)
    {
        $this->createFromTrait($element);
        return $element;
    }

    /**
     * @param ApplicationElement $element
     * @return ApplicationElement
     */
    public function update(ApplicationElement $element)
    {
        $this->updateFromTrait($element);
        return $element;
    }

    /**
     * @param ApplicationElement $element
     * @return ApplicationElement
     */
    public function restore(ApplicationElement $element)
    {
        $this->restoreFromTrait($element);
        return $element;
    }

    /**
     * @param ApplicationElement $element
     * @return ApplicationElement
     */
    public function historise(ApplicationElement $element)
    {
        $this->historiserFromTrait($element);
        return $element;
    }

    /**
     * @param ApplicationElement $element
     * @return ApplicationElement
     */
    public function delete(ApplicationElement $element)
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
        $qb = $this->getEntityManager()->getRepository(ApplicationElement::class)->createQueryBuilder('applicationelement')
//            ->addSelect('application')->join('applicationelement.application', 'element')
;
        return $qb;
    }

    /**
     * @param int $id
     * @return ApplicationElement
     */
    public function getApplicationElement(int $id) : ApplicationElement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('applicationelement.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ApplicationElement partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return ApplicationElement
     */
    public function getRequestedApplicationElement(AbstractActionController $controller, string $param = "application-element")
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getApplicationElement($id);
    }

}