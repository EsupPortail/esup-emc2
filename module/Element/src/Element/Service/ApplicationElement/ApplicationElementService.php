<?php

namespace Element\Service\ApplicationElement;

use Element\Entity\Db\ApplicationElement;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class ApplicationElementService {
    use EntityManagerAwareTrait;
    
    /** Gestion des entites ***************************************************************************************/

    public function create(ApplicationElement $element) : ApplicationElement
    {
        try {
            $this->getEntityManager()->persist($element);
            $this->getEntityManager()->flush($element);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $element;
    }

    public function update(ApplicationElement $element) : ApplicationElement
    {
        try {
            $this->getEntityManager()->flush($element);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $element;
    }

    public function historise(ApplicationElement $element) : ApplicationElement
    {
        try {
            $element->historiser();
            $this->getEntityManager()->flush($element);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $element;
    }

    public function restore(ApplicationElement $element) : ApplicationElement
    {
        try {
            $element->dehistoriser();
            $this->getEntityManager()->flush($element);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $element;
    }

    public function delete(ApplicationElement $element) : ApplicationElement
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
        $qb = $this->getEntityManager()->getRepository(ApplicationElement::class)->createQueryBuilder('applicationelement')
            ->addSelect('application')->join('applicationelement.application', 'application')
;
        return $qb;
    }

    public function getApplicationElement(int $id) : ?ApplicationElement
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

    public function getRequestedApplicationElement(AbstractActionController $controller, string $param = "application-element") : ?ApplicationElement
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getApplicationElement($id);
    }

}