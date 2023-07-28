<?php

namespace Formation\Service\FormationElement;

use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationElement;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class FormationElementService {
    use EntityManagerAwareTrait;
    
    /** Gestion des entites ***************************************************************************************/

    /**
     * @param FormationElement $element
     * @return FormationElement
     */
    public function create(FormationElement $element) : FormationElement
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
     * @param FormationElement $element
     * @return FormationElement
     */
    public function update(FormationElement $element) : FormationElement
    {
        try {
            $this->getEntityManager()->flush($element);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $element;
    }

    /**
     * @param FormationElement $element
     * @return FormationElement
     */
    public function restore(FormationElement $element) : FormationElement
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
     * @param FormationElement $element
     * @return FormationElement
     */
    public function historise(FormationElement $element) : FormationElement
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
     * @param FormationElement $element
     * @return FormationElement
     */
    public function delete(FormationElement $element) : FormationElement
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
        try {
            $qb = $this->getEntityManager()->getRepository(FormationElement::class)->createQueryBuilder('formationelement')
                ->addSelect('formation')->join('formationelement.formation', 'formation');
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problème est survenu lors de la création du QueryBuilder de [" . FormationElement::class . "]", 0, $e);
        }
        return $qb;
    }

    /**
     * @param int $id
     * @return FormationElement|null
     */
    public function getFormationElement(int $id) : ?FormationElement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formationelement.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationElement partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return FormationElement|null
     */
    public function getRequestedFormationElement(AbstractActionController $controller, string $param = "formation-element") : ?FormationElement
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