<?php

namespace Element\Service\CompetenceElement;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class CompetenceElementService {
    use EntityManagerAwareTrait;
    
    /** Gestion des entites ***************************************************************************************/

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

    public function update(CompetenceElement $element) : CompetenceElement
    {
        try {
            $this->getEntityManager()->flush($element);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $element;
    }

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

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(CompetenceElement::class)->createQueryBuilder('competenceelement')
            ->addSelect('competence')->join('competenceelement.competence', 'competence')
            ->addSelect('niveau')->leftjoin('competenceelement.niveau', 'niveau')
        ;
        return $qb;
    }

    public function getCompetenceElement(int $id) : ?CompetenceElement
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

    public function getRequestedCompetenceElement(AbstractActionController $controller, string $param = "competence-element") : ?CompetenceElement
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getCompetenceElement($id);
    }

    /** @return CompetenceElement[] */
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