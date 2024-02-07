<?php

namespace Formation\Service\FormationElement;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationElement;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;

class FormationElementService
{
    use ProvidesObjectManager;

    /** Gestion des entites ***************************************************************************************/

    /**
     * @param FormationElement $element
     * @return FormationElement
     */
    public function create(FormationElement $element): FormationElement
    {
        $this->getObjectManager()->persist($element);
        $this->getObjectManager()->flush($element);
        return $element;
    }

    /**
     * @param FormationElement $element
     * @return FormationElement
     */
    public function update(FormationElement $element): FormationElement
    {
        $this->getObjectManager()->flush($element);
        return $element;
    }

    /**
     * @param FormationElement $element
     * @return FormationElement
     */
    public function restore(FormationElement $element): FormationElement
    {
        $element->historiser();
        $this->getObjectManager()->flush($element);
        return $element;
    }

    /**
     * @param FormationElement $element
     * @return FormationElement
     */
    public function historise(FormationElement $element): FormationElement
    {
        $element->dehistoriser();
        $this->getObjectManager()->flush($element);
        return $element;
    }

    /**
     * @param FormationElement $element
     * @return FormationElement
     */
    public function delete(FormationElement $element): FormationElement
    {
        $this->getObjectManager()->remove($element);
        $this->getObjectManager()->flush($element);
        return $element;
    }

    /** REQUETAGE  ****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(FormationElement::class)->createQueryBuilder('formationelement')
            ->addSelect('formation')->join('formationelement.formation', 'formation');
        return $qb;
    }

    /**
     * @param int $id
     * @return FormationElement|null
     */
    public function getFormationElement(int $id): ?FormationElement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formationelement.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationElement partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return FormationElement|null
     */
    public function getRequestedFormationElement(AbstractActionController $controller, string $param = "formation-element"): ?FormationElement
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getFormationElement($id);
    }

    /**
     * @param Formation $formation
     * @return FormationElement[]
     */
    public function getElementsByFormation(Formation $formation): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formationelement.formation = :formation')
            ->setParameter('formation', $formation);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

}