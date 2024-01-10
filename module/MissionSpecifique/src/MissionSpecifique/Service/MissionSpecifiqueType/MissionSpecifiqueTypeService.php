<?php

namespace MissionSpecifique\Service\MissionSpecifiqueType;

use Doctrine\ORM\NonUniqueResultException;
use DoctrineModule\Persistence\ProvidesObjectManager;
use MissionSpecifique\Entity\Db\MissionSpecifiqueType;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class MissionSpecifiqueTypeService {
    use ProvidesObjectManager;

    /** GESTION ENTITES *********************************************************************************/

    public function create(MissionSpecifiqueType $type) : MissionSpecifiqueType
    {
        $this->getObjectManager()->persist($type);
        $this->getObjectManager()->flush($type);
        return $type;
    }

    public function update(MissionSpecifiqueType $type)  : MissionSpecifiqueType
    {
        $this->getObjectManager()->flush($type);
        return $type;
    }

    public function historise(MissionSpecifiqueType $type) : MissionSpecifiqueType
    {
        $type->historiser();
        $this->getObjectManager()->flush($type);
        return $type;
    }

    public function restore(MissionSpecifiqueType $type) : MissionSpecifiqueType
    {
        $type->dehistoriser();
        $this->getObjectManager()->flush($type);
        return $type;
    }

    public function delete(MissionSpecifiqueType $type) : MissionSpecifiqueType
    {
        $this->getObjectManager()->remove($type);
        $this->getObjectManager()->flush($type);
        return $type;
    }

    /** REQUETAGE *****************************************************************************************************/

    /* @return MissionSpecifiqueType[] */
    public function getMissionsSpecifiquesTypes(bool $historiser= true, string $champ = 'libelle', string $ordre ='ASC') : array
    {
        $qb = $this->getObjectManager()->getRepository(MissionSpecifiqueType::class)->createQueryBuilder('type')
            ->addSelect('mission')->leftJoin('type.missions', 'mission')
            ->orderBy('type.' . $champ, $ordre)
        ;

        if ($historiser === false) {
            $qb = $qb->andWhere('type.histoDestruction IS NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /* @return string[] */
    public function getMissionsSpecifiquesTypesAsOptions(bool $historiser= false, string $champ = 'libelle', string $ordre ='ASC') : array
    {
        $types = $this->getMissionsSpecifiquesTypes($historiser, $champ, $ordre);
        $array = [];
        foreach ($types as $type) {
            $array[$type->getId()] = $type->getLibelle();
        }
        return $array;
    }

    public function getMissionSpecifiqueType(?int $id) : ?MissionSpecifiqueType
    {
        $qb = $this->getObjectManager()->getRepository(MissionSpecifiqueType::class)->createQueryBuilder('type')
            ->andWhere('type.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs MissionSpecifiqueType partagent le même id ['.$id.'].', 0, $e);
        }
        return $result;
    }

    public function getRequestedMissionSpecifiqueType(AbstractActionController $controller, string $paramName = 'type') : ?MissionSpecifiqueType
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getMissionSpecifiqueType($id);
        return $result;
    }
}