<?php

namespace FicheMetier\Service\MissionElement;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Entity\Db\MissionElement;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\Interface\HasActivitesInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class MissionElementService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITÉS *******************************************************************************************/

    public function create(MissionElement $missionElement): void
    {
        $this->getObjectManager()->persist($missionElement);
        $this->getObjectManager()->flush($missionElement);
    }

    public function update(MissionElement $missionElement): void
    {
        $this->getObjectManager()->flush($missionElement);
    }

    public function historise(MissionElement $missionElement): void
    {
        $missionElement->historiser();
        $this->getObjectManager()->flush($missionElement);
    }

    public function restore(MissionElement $missionElement): void
    {
        $missionElement->dehistoriser();
        $this->getObjectManager()->flush($missionElement);
    }

    public function delete(MissionElement $missionElement): void
    {
        $this->getObjectManager()->remove($missionElement);
        $this->getObjectManager()->flush($missionElement);
    }

    /** REQUÊTAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(MissionElement::class)->createQueryBuilder('missionElement')
            ->addSelect('mission')->join('missionElement.mission', 'mission')
        ;
        return $qb;
    }

    public function getMissionsElements(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder();
        if (!$withHisto) {
            $qb = $qb
                ->andWhere('missionElement.histoDestruction IS NULL')
                ->andWhere('mission.histoDestruction IS NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getMissionElement(?int $id): ?MissionElement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('missionElement.id = :id')->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".MissionElement::class."] partagent le même id [".$id."]", 0 , $e);
        }
        return $result;
    }

    public function getResquestedMissionElement(AbstractActionController $controller, string $param = 'mission-element'): ?MissionElement
    {
        $id = $controller->params()->fromRoute($param);
        $missionElement = $this->getMissionElement($id);
        return $missionElement;
    }

    /** FAÇADE ********************************************************************************************************/

    public function addMissionElement(HasActivitesInterface $object, Mission $mission, ?string $description = null, int $position = MissionElement::MAX_POSITION, bool $flush = true): void
    {
        $element = new MissionElement();
        $element->setMission($mission);
        $element->setDescription($description);
        $element->setPosition($position);
        $object->addMission($element);

        if ($flush) {
            $this->create($element);
            $this->getObjectManager()->flush($object);
        }
    }

    public function reorder(HasActivitesInterface $ficheMetier): void
    {
        $missions = $ficheMetier->getMissions();
        usort($missions, function (MissionElement $a, MissionElement $b) {
            if ($a->getPosition() === $b->getPosition()) return $a->getMission()->getLibelle() <=> $b->getMission()->getLibelle();
            return $a->getPosition() <=> $b->getPosition();
        });

        $position = 1 ;
        foreach ($missions as $mission) {
            $mission->setPosition($position);
            $position++;
        }
        $this->getObjectManager()->flush($missions);
    }

    public function move(?FicheMetier $ficheMetier, ?MissionElement $missionElement, int $direction): void
    {
        $this->reorder($ficheMetier);
        $missions = $ficheMetier->getMissions();

        $ranking = []; $position = 1; $elementRanking = null;
        foreach ($missions as $mission) {
            $ranking[$position] = $mission;
            if ($mission === $missionElement) $elementRanking = $position;
            $position++;
        }

        if ($elementRanking !== null) {
            $otherRanking =  $elementRanking + $direction;

            if (isset($ranking[$otherRanking])) {
                $ranking[$otherRanking]->setPosition($elementRanking);
                $ranking[$elementRanking]->setPosition($otherRanking);
                $this->getObjectManager()->flush($ranking[$elementRanking]);
                $this->getObjectManager()->flush($ranking[$otherRanking]);
            }
        }
    }


    /** @var MissionElement[] $missionsElements */
    public function getMissionElementFromArray(array $missionsElements, Mission $mission): ?MissionElement
    {
        foreach ($missionsElements as $missionElement) {
            if ($missionElement->getMission() === $mission) return $missionElement;
        }
        return null;
    }
}
