<?php

namespace Application\Service\CompetenceElement;

use Application\Entity\Db\Competence;
use Application\Entity\Db\CompetenceElement;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use phpDocumentor\Reflection\Types\Array_;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class CompetenceElementService {
    use GestionEntiteHistorisationTrait;
    
    /** Gestion des entites ***************************************************************************************/

    /**
     * @param CompetenceElement $element
     * @return CompetenceElement
     */
    public function create(CompetenceElement $element)
    {
        $this->createFromTrait($element);
        return $element;
    }

    /**
     * @param CompetenceElement $element
     * @return CompetenceElement
     */
    public function update(CompetenceElement $element)
    {
        $this->updateFromTrait($element);
        return $element;
    }

    /**
     * @param CompetenceElement $element
     * @return CompetenceElement
     */
    public function restore(CompetenceElement $element)
    {
        $this->restoreFromTrait($element);
        return $element;
    }

    /**
     * @param CompetenceElement $element
     * @return CompetenceElement
     */
    public function historise(CompetenceElement $element)
    {
        $this->historiserFromTrait($element);
        return $element;
    }

    /**
     * @param CompetenceElement $element
     * @return CompetenceElement
     */
    public function delete(CompetenceElement $element)
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
        $qb = $this->getEntityManager()->getRepository(CompetenceElement::class)->createQueryBuilder('competenceelement')
            ->addSelect('competence')->join('competenceelement.competence', 'competence')
        ;
        return $qb;
    }

    /**
     * @param int $id
     * @return CompetenceElement
     */
    public function getCompetenceElement(int $id) : CompetenceElement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competenceelement.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs CompetenceElement partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return CompetenceElement
     */
    public function getRequestedCompetenceElement(AbstractActionController $controller, string $param = "competence-element")
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getCompetenceElement($id);
    }

    /**
     * @param Competence $competence
     * @return CompetenceElement[]
     */
    public function getElementsByCompetence(Competence $competence) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competenceelement.competence = :competence')
            ->setParameter('competence', $competence)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

}