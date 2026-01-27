<?php

namespace FicheMetier\Service\ActiviteElement;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\Activite;
use FicheMetier\Entity\Db\ActiviteElement;
use FicheMetier\Entity\Db\FicheMetier;
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

    public function reorder(HasActivitesInterface $ficheMetier): void
    {
        $activites = $ficheMetier->getActivites();
        usort($activites, function (ActiviteElement $a, ActiviteElement $b) {
            if ($a->getPosition() === $b->getPosition()) return $a->getActivite()->getLibelle() <=> $b->getActivite()->getLibelle();
            return $a->getPosition() <=> $b->getPosition();
        });

        $position = 1 ;
        foreach ($activites as $activite) {
            $activite->setPosition($position);
            $position++;
        }
        $this->getObjectManager()->flush($activites);
    }

    public function move(?FicheMetier $ficheMetier, ?ActiviteElement $activiteElement, int $direction): void
    {
        $this->reorder($ficheMetier);
        $activites = $ficheMetier->getActivites();

        $ranking = []; $position = 1; $elementRanking = null;
        foreach ($activites as $activite) {
            $ranking[$position] = $activite;
            $position++;
        }
        $elementRanking = $activiteElement->getPosition();

        if ($elementRanking !== null) {
            $otherRanking =  $elementRanking + $direction;

            if (isset($ranking[$otherRanking])) {
                $ranking[$otherRanking]->setPosition($elementRanking);
                $activiteElement->setPosition($otherRanking);
                $this->getObjectManager()->flush($ranking[$elementRanking]);
                $this->getObjectManager()->flush($ranking[$otherRanking]);
            }
        }
    }

    /** @var ActiviteElement[] $activitesElements */
    public function getActiviteElementFromArray(array $activitesElements, Activite $activite): ?ActiviteElement
    {
        foreach ($activitesElements as $activiteElement) {
            if ($activiteElement->getActivite() === $activite) return $activiteElement;
        }
        return null;
    }
}
