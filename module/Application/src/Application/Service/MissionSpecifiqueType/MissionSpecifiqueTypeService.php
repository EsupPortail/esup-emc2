<?php

namespace Application\Service\MissionSpecifiqueType;

use Application\Entity\Db\MissionSpecifiqueType;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class MissionSpecifiqueTypeService {
    use EntityManagerAwareTrait;

    /** GESTION ENTITES *********************************************************************************/

    /**
     * @param MissionSpecifiqueType $type
     * @return MissionSpecifiqueType
     */
    public function create(MissionSpecifiqueType $type) : MissionSpecifiqueType
    {
        try {
            $this->getEntityManager()->persist($type);
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue dans l'enregistrement en base.",0,$e);
        }
        return $type;
    }

    /**
     * @param MissionSpecifiqueType $type
     * @return MissionSpecifiqueType
     */
    public function update(MissionSpecifiqueType $type)  : MissionSpecifiqueType
    {
        try {
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue dans l'enregistrement en base.",0,$e);
        }
        return $type;
    }

    /**
     * @param MissionSpecifiqueType $type
     * @return MissionSpecifiqueType
     */
    public function historise(MissionSpecifiqueType $type) : MissionSpecifiqueType
    {
        try {
            $type->historiser();
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue dans l'enregistrement en base.",0,$e);
        }
        return $type;
    }

    /**
     * @param MissionSpecifiqueType $type
     * @return MissionSpecifiqueType
     */
    public function restore(MissionSpecifiqueType $type) : MissionSpecifiqueType
    {
        try {
            $type->dehistoriser();
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue dans l'enregistrement en base.",0,$e);
        }
        return $type;
    }

    /**
     * @param MissionSpecifiqueType $type
     * @return MissionSpecifiqueType
     */
    public function delete(MissionSpecifiqueType $type) : MissionSpecifiqueType
    {
        try {
            $this->getEntityManager()->remove($type);
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue dans l'enregistrement en base.",0,$e);
        }
        return $type;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @param bool $historiser
     * @param string $champ
     * @param string $ordre
     * @return MissionSpecifiqueType[]
     */
    public function getMissionsSpecifiquesTypes(bool $historiser= true, string $champ = 'libelle', string $ordre ='ASC') : array
    {
        $qb = $this->getEntityManager()->getRepository(MissionSpecifiqueType::class)->createQueryBuilder('type')
            ->addSelect('mission')->leftJoin('type.missions', 'mission')
            ->orderBy('type.' . $champ, $ordre)
        ;

        if ($historiser === false) {
            $qb = $qb->andWhere('type.histoDestruction IS NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param bool $historiser
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getMissionsSpecifiquesTypesAsOptions(bool $historiser= false, string $champ = 'libelle', string $ordre ='ASC') : array
    {
        $types = $this->getMissionsSpecifiquesTypes($historiser, $champ, $ordre);
        $array = [];
        foreach ($types as $type) {
            $array[$type->getId()] = $type->getLibelle();
        }
        return $array;
    }

    /**
     * @param int|null $id
     * @return MissionSpecifiqueType
     */
    public function getMissionSpecifiqueType(?int $id) : ?MissionSpecifiqueType
    {
        $qb = $this->getEntityManager()->getRepository(MissionSpecifiqueType::class)->createQueryBuilder('type')
            ->andWhere('type.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (ORMException $e) {
            throw new RuntimeException('Plusieurs MissionSpecifiqueType partagent le même id ['.$id.'].', $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return MissionSpecifiqueType
     */
    public function getRequestedMissionSpecifiqueType(AbstractActionController $controller, string $paramName = 'type') : ?MissionSpecifiqueType
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getMissionSpecifiqueType($id);
        return $result;
    }
}