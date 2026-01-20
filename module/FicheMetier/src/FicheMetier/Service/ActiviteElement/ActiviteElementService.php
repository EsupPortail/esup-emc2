<?php

namespace FicheMetier\Service\ActiviteElement;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectManager;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\Activite;
use FicheMetier\Entity\Db\ActiviteElement;
use FicheMetier\Entity\Db\Interface\HasActivitesInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class ActiviteElementService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITÉS *******************************************************************************************/

    public function create(ActiviteElement $activiteElement): void
    {
        $this->getObjectManager()->persist($activiteElement);
        $this->getObjectManager()->flush($activiteElement);
    }

    public function update(ActiviteElement $activiteElement): void
    {
        $this->getObjectManager()->flush($activiteElement);
    }

    public function historise(ActiviteElement $activiteElement): void
    {
        $activiteElement->historiser();
        $this->getObjectManager()->flush($activiteElement);
    }

    public function restore(ActiviteElement $activiteElement): void
    {
        $activiteElement->dehistoriser();
        $this->getObjectManager()->flush($activiteElement);
    }

    public function delete(ActiviteElement $activiteElement): void
    {
        $this->getObjectManager()->remove($activiteElement);
        $this->getObjectManager()->flush($activiteElement);
    }

    /** REQUÊTAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(ActiviteElement::class)->createQueryBuilder('activiteElement')
            ->addSelect('activite')->join('activiteElement.activite', 'activite')
        ;
        return $qb;
    }

    public function getActivitesElements(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder();
        if (!$withHisto) {
            $qb = $qb
                ->andWhere('activiteElement.histoDestruction IS NULL')
                ->andWhere('activite.histoDestruction IS NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getActiviteElement(?int $id): ?ActiviteElement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('activiteElement.id = :id')->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".ActiviteElement::class."] partagent le même id [".$id."]", 0 , $e);
        }
        return $result;
    }

    public function getResquestedActiviteElement(AbstractActionController $controller, string $param = 'activite-element'): ?ActiviteElement
    {
        $id = $controller->params()->fromRoute($param);
        $activiteElement = $this->getActiviteElement($id);
        return $activiteElement;
    }

    /** FAÇADE ********************************************************************************************************/

    public function addActiviteElement(HasActivitesInterface $object, Activite $activite, ?string $description = null, int $position = ActiviteElement::MAX_POSITION, bool $flush = true): void
    {
        $element = new ActiviteElement();
        $element->setActivite($activite);
        $element->setDescription($description);
        $element->setPosition($position);
        $object->addActivite($element);

        if ($flush) {
            $this->create($element);
            $this->getObjectManager()->flush($object);
        }
    }
}
