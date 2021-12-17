<?php

namespace Application\Service\CompetenceElement;

use Application\Entity\Db\Competence;
use Application\Entity\Db\CompetenceElement;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class CompetenceElementService {
    use EntityManagerAwareTrait;
    
    /** Gestion des entites ***************************************************************************************/

    /**
     * @param CompetenceElement $element
     * @return CompetenceElement
     */
    public function create(CompetenceElement $element) : CompetenceElement
    {
        try {
            $this->getEntityManager()->persist($element);
            $this->getEntityManager()->flush($element);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $element;
    }

    /**
     * @param CompetenceElement $element
     * @return CompetenceElement
     */
    public function update(CompetenceElement $element) : CompetenceElement
    {
        try {
            $this->getEntityManager()->flush($element);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $element;
    }

    /**
     * @param CompetenceElement $element
     * @return CompetenceElement
     */
    public function historise(CompetenceElement $element) : CompetenceElement
    {
        try {
            $element->historiser();
            $this->getEntityManager()->flush($element);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $element;
    }

    /**
     * @param CompetenceElement $element
     * @return CompetenceElement
     */
    public function restore(CompetenceElement $element) : CompetenceElement
    {
        try {
            $element->dehistoriser();
            $this->getEntityManager()->flush($element);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $element;
    }

    /**
     * @param CompetenceElement $element
     * @return CompetenceElement
     */
    public function delete(CompetenceElement $element) : CompetenceElement
    {
        try {
            $this->getEntityManager()->remove($element);
            $this->getEntityManager()->flush($element);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
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
            ->addSelect('niveau')->leftjoin('competenceelement.niveau', 'niveau')
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
     * @return CompetenceElement|null
     */
    public function getRequestedCompetenceElement(AbstractActionController $controller, string $param = "competence-element") : ?CompetenceElement
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