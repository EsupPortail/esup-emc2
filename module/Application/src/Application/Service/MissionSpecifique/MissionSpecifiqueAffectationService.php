<?php

namespace Application\Service\MissionSpecifique;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentMissionSpecifique;
use Application\Entity\Db\MissionSpecifique;
use Application\Entity\Db\MissionSpecifiqueTheme;
use Application\Entity\Db\MissionSpecifiqueType;
use Application\Entity\Db\Structure;
use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class MissionSpecifiqueAffectationService {
    use DateTimeAwareTrait;
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** MISSION SPECIFIQUE ********************************************************************************************/

    /**
     * @return MissionSpecifique[]
     */
    public function getMissionsSpecifiques() {
        $qb = $this->getEntityManager()->getRepository(MissionSpecifique::class)->createQueryBuilder('mission')
            ->addSelect('type')->leftJoin('mission.type', 'type')
            ->addSelect('theme')->leftJoin('mission.theme', 'theme')
            ->addSelect('affectation')->leftJoin('mission.affectations', 'affectation')
            ->addSelect('modificateur')->join('mission.histoModificateur', 'modificateur')
            ->orderBy('mission.libelle', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return MissionSpecifique[]
     */
    public function getMisssionsSpecifiquesAsOption()
    {
        $missions = $this->getMissionsSpecifiques();
        $options = [];
        foreach ($missions as $mission) {
            $options[$mission->getId()] = $mission->getLibelle();
        }
        return $options;
    }

    /**
     * @param bool $historiser
     * @param string $champ
     * @param string $ordre
     * @return MissionSpecifique[]
     */
    public function getMisssionsSpecifiquesAsGroupOptions($historiser = false, $champ = 'libelle', $ordre = 'ASC')
    {
        $themes = $this->getMissionsSpecifiquesThemes($historiser, $champ, $ordre);
        $sanstheme = $this->getMissionsSpecifiquesSansTheme();
        $options = [];

        foreach ($themes as $theme) {
            $optionsoptions = [];
            foreach ($theme->getMissions() as $mission) {
                $optionsoptions[$mission->getId()] = $mission->getLibelle();
            }
            asort($optionsoptions);
            $array = [
                'label' => $theme->getLibelle(),
                'options' => $optionsoptions,
            ];
            $options[] = $array;
        }

        if (!empty($sanstheme)) {
            $optionsoptions = [];
            foreach ($sanstheme as $mission) {
                $optionsoptions[$mission->getId()] = $mission->getLibelle();
            }
            asort($optionsoptions);
            $array = [
                'label' => "Sans thème",
                'options' => $optionsoptions,
            ];
            $options[] = $array;
        }

        return $options;
    }

    /**
     * @param integer $id
     * @return MissionSpecifique
     */
    public function getMissionSpecifique($id) {
        $qb = $this->getEntityManager()->getRepository(MissionSpecifique::class)->createQueryBuilder('mission')
            ->andWhere('mission.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs MissionSpecifique partagent le même id [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return MissionSpecifique
     */
    public function getRequestedMissionSpecifique($controller, $paramName = 'mission')
    {
        $id = $controller->params()->fromRoute($paramName);
        $mission = $this->getMissionSpecifique($id);
        return $mission;
    }

    /**
     * @return MissionSpecifique[]
     */
    private function getMissionsSpecifiquesSansTheme()
    {
        $qb = $this->getEntityManager()->getRepository(MissionSpecifique::class)->createQueryBuilder('mission')
            ->orderBy('mission.libelle', 'ASC')
            ->andWhere('mission.theme IS NULL')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param MissionSpecifique $mission
     * @return MissionSpecifique
     */
    public function createMissionSpecifique($mission)
    {
        try {
            $user = $this->getUserService()->getConnectedUser();
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }

        $mission->setHistoCreation($date);
        $mission->setHistoCreateur($user);
        $mission->setHistoModification($date);
        $mission->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($mission);
            $this->getEntityManager()->flush($mission);
        } catch (ORMException $e) {
            throw new RuntimeException("Problème lors de la sauvegarde en BD", $e);
        }

        return $mission;
    }

    /**
     * @param MissionSpecifique $mission
     * @return MissionSpecifique
     */
    public function updateMissionSpecifique($mission)
    {
        $user = $this->getUserService()->getConnectedUser();
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }

        $mission->setHistoModification($date);
        $mission->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($mission);
        } catch (ORMException $e) {
            throw new RuntimeException("Problème lors de la sauvegarde en BD", $e);
        }

        return $mission;
    }

    /**
     * @param MissionSpecifique $mission
     * @return MissionSpecifique
     */
    public function historiseMissionSpecifique($mission)
    {
        $user = $this->getUserService()->getConnectedUser();
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }

        $mission->setHistoDestruction($date);
        $mission->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($mission);
        } catch (ORMException $e) {
            throw new RuntimeException("Problème lors de la sauvegarde en BD", $e);
        }

        return $mission;
    }

    /**
     * @param MissionSpecifique $mission
     * @return MissionSpecifique
     */
    public function restoreMissionSpecifique($mission)
    {
        $mission->setHistoDestruction(null);
        $mission->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($mission);
        } catch (ORMException $e) {
            throw new RuntimeException("Problème lors de la sauvegarde en BD", $e);
        }

        return $mission;
    }

    /**
     * @param MissionSpecifique $mission
     * @return MissionSpecifique
     */
    public function deleteMissionSpecifique($mission)
    {
        try {
            $this->getEntityManager()->remove($mission);
            $this->getEntityManager()->flush($mission);
        } catch (ORMException $e) {
            throw new RuntimeException("Problème lors de la sauvegarde en BD", $e);
        }

        return $mission;
    }

    /** MISSION SPECIFIQUE TYPE ***************************************************************************************/

    /**
     * @param bool $historiser
     * @param string $champ
     * @param string $ordre
     * @return MissionSpecifiqueType[]
     */
    public function getMissionsSpecifiquesTypes($historiser= false, $champ = 'libelle', $ordre ='ASC')
    {
        $qb = $this->getEntityManager()->getRepository(MissionSpecifiqueType::class)->createQueryBuilder('type')
            ->addSelect('mission')->leftJoin('type.missions', 'mission')
            ->addSelect('modificateur')->join('type.histoModificateur', 'modificateur')
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
    public function getMissionsSpecifiquesTypesAsOptions($historiser= false, $champ = 'libelle', $ordre ='ASC')
    {
        $types = $this->getMissionsSpecifiquesTypes($historiser, $champ, $ordre);
        $array = [];
        foreach ($types as $type) {
            $array[$type->getId()] = $type->getLibelle();
        }
        return $array;
    }

    /**
     * @param integer $id
     * @return MissionSpecifiqueType
     */
    public function getMissionSpecifiqueType($id)
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
    public function getRequestedMissionSpecifiqueType($controller, $paramName = 'mission-specifique-type')
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getMissionSpecifiqueType($id);
        return $result;
    }

    /**
     * @param MissionSpecifiqueType $type
     * @return MissionSpecifiqueType
     */
    public function createMissionSpecifiqueType($type)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch(Exception $e) {
            throw new RuntimeException("Un erreur s'est produite lors de la récupération des données d'historisation", $e);
        }
        $type->setHistoCreateur($user);
        $type->setHistoCreation($date);
        $type->setHistoModificateur($user);
        $type->setHistoModification($date);

        try {
            $this->getEntityManager()->persist($type);
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un erreur s'est produite lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param MissionSpecifiqueType $type
     * @return MissionSpecifiqueType
     */
    public function updateMissionSpecifiqueType($type)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch(Exception $e) {
            throw new RuntimeException("Un erreur s'est produite lors de la récupération des données d'historisation", $e);
        }
        $type->setHistoModificateur($user);
        $type->setHistoModification($date);

        try {
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un erreur s'est produite lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param MissionSpecifiqueType $type
     * @return MissionSpecifiqueType
     */
    public function historizeMissionSpecifiqueType($type)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch(Exception $e) {
            throw new RuntimeException("Un erreur s'est produite lors de la récupération des données d'historisation", $e);
        }
        $type->setHistoDestructeur($user);
        $type->setHistoDestruction($date);

        try {
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un erreur s'est produite lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param MissionSpecifiqueType $type
     * @return MissionSpecifiqueType
     */
    public function restoreMissionSpecifiqueType($type)
    {
        $type->setHistoDestructeur(null);
        $type->setHistoDestruction(null);

        try {
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un erreur s'est produite lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param MissionSpecifiqueType $type
     * @return MissionSpecifiqueType
     */
    public function deleteMissionSpecifiqueType($type)
    {
        try {
            $this->getEntityManager()->remove($type);
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un erreur s'est produite lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /** MISSION SPECIFIQUE THEME **************************************************************************************/

    /**
     * @param bool $historiser
     * @param string $champ
     * @param string $ordre
     * @return MissionSpecifiqueTheme[]
     */
    public function getMissionsSpecifiquesThemes($historiser= false, $champ = 'libelle', $ordre ='ASC')
    {
        $qb = $this->getEntityManager()->getRepository(MissionSpecifiqueTheme::class)->createQueryBuilder('theme')
            ->addSelect('mission')->leftJoin('theme.missions', 'mission')
            ->addSelect('modificateur')->join('theme.histoModificateur', 'modificateur')
            ->orderBy('theme.' . $champ, $ordre)
        ;

        if ($historiser === false) {
            $qb = $qb->andWhere('theme.histoDestruction IS NULL');
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
    public function getMissionsSpecifiquesThemesAsOptions($historiser= false, $champ = 'libelle', $ordre ='ASC')
    {
        $types = $this->getMissionsSpecifiquesThemes($historiser, $champ, $ordre);
        $array = [];
        foreach ($types as $type) {
            $array[$type->getId()] = $type->getLibelle();
        }
        return $array;
    }

    /**
     * @param integer $id
     * @return MissionSpecifiqueTheme
     */
    public function getMissionSpecifiqueTheme($id)
    {
        $qb = $this->getEntityManager()->getRepository(MissionSpecifiqueTheme::class)->createQueryBuilder('type')
            ->andWhere('type.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (ORMException $e) {
            throw new RuntimeException('Plusieurs MissionSpecifiqueTheme partagent le même id ['.$id.'].', $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return MissionSpecifiqueTheme
     */
    public function getRequestedMissionSpecifiqueTheme($controller, $paramName = 'mission-specifique-theme')
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getMissionSpecifiqueTheme($id);
        return $result;
    }

    /**
     * @param MissionSpecifiqueTheme $type
     * @return MissionSpecifiqueTheme
     */
    public function createMissionSpecifiqueTheme($type)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch(Exception $e) {
            throw new RuntimeException("Un erreur s'est produite lors de la récupération des données d'historisation", $e);
        }
        $type->setHistoCreateur($user);
        $type->setHistoCreation($date);
        $type->setHistoModificateur($user);
        $type->setHistoModification($date);

        try {
            $this->getEntityManager()->persist($type);
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un erreur s'est produite lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param MissionSpecifiqueTheme $type
     * @return MissionSpecifiqueTheme
     */
    public function updateMissionSpecifiqueTheme($type)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch(Exception $e) {
            throw new RuntimeException("Un erreur s'est produite lors de la récupération des données d'historisation", $e);
        }
        $type->setHistoModificateur($user);
        $type->setHistoModification($date);

        try {
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un erreur s'est produite lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param MissionSpecifiqueTheme $type
     * @return MissionSpecifiqueTheme
     */
    public function historizeMissionSpecifiqueTheme($type)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch(Exception $e) {
            throw new RuntimeException("Un erreur s'est produite lors de la récupération des données d'historisation", $e);
        }
        $type->setHistoDestructeur($user);
        $type->setHistoDestruction($date);

        try {
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un erreur s'est produite lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param MissionSpecifiqueTheme $type
     * @return MissionSpecifiqueTheme
     */
    public function restoreMissionSpecifiqueTheme($type)
    {
        $type->setHistoDestructeur(null);
        $type->setHistoDestruction(null);

        try {
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un erreur s'est produite lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /**
     * @param MissionSpecifiqueTheme $type
     * @return MissionSpecifiqueTheme
     */
    public function deleteMissionSpecifiqueTheme($type)
    {
        try {
            $this->getEntityManager()->remove($type);
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un erreur s'est produite lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /** ENTITY MANAGEMENT *********************************************************************************************/

    /**
     * @param AgentMissionSpecifique $affectation
     * @return AgentMissionSpecifique
     */
    public function create($affectation)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $affectation->setHistoCreation($date);
        $affectation->setHistoCreateur($user);
        $affectation->setHistoModification($date);
        $affectation->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($affectation);
            $this->getEntityManager()->flush($affectation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'un AgentMissionSpecifique", $e);
        }
        return $affectation;
    }

    /**
     * @param AgentMissionSpecifique $affectation
     * @return AgentMissionSpecifique
     */
    public function update($affectation)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $affectation->setHistoModification($date);
        $affectation->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($affectation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'un AgentMissionSpecifique", $e);
        }
        return $affectation;
    }

    /**
     * @param AgentMissionSpecifique $affectation
     * @return AgentMissionSpecifique
     */
    public function historise($affectation)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $affectation->setHistoDestruction($date);
        $affectation->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($affectation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'un AgentMissionSpecifique", $e);
        }
        return $affectation;
    }

    /**
     * @param AgentMissionSpecifique $affectation
     * @return AgentMissionSpecifique
     */
    public function restore($affectation)
    {
        $affectation->setHistoDestruction(null);
        $affectation->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($affectation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'un AgentMissionSpecifique", $e);
        }
        return $affectation;
    }

    /**
     * @param AgentMissionSpecifique $affectation
     * @return AgentMissionSpecifique
     */
    public function delete($affectation)
    {
        try {
            $this->getEntityManager()->remove($affectation);
            $this->getEntityManager()->flush($affectation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'un AgentMissionSpecifique", $e);
        }
        return $affectation;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() {

        $qb = $this->getEntityManager()->getRepository(AgentMissionSpecifique::class)->createQueryBuilder('affectation')
            ->addSelect('agent')->leftJoin('affectation.agent', 'agent')
            ->addSelect('mission')->leftJoin('affectation.mission', 'mission')
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
        ;

        return $qb;
    }

    /**
     * @param Agent $agent
     * @param MissionSpecifique $mission
     * @param Structure $structure
     * @return AgentMissionSpecifique[]
     */
    public function getAffectations($agent, $mission, $structure)
    {
        $qb = $this->getEntityManager()->getRepository(AgentMissionSpecifique::class)->createQueryBuilder('affectation')
            ->addSelect('agent')->leftJoin('affectation.agent', 'agent')
            ->addSelect('mission')->leftJoin('affectation.mission', 'mission')
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
        ;

        if ($agent !== null) {
            $qb = $qb->andWhere('agent.id = :agentId')
                ->setParameter('agentId', $agent->getId())
            ;
        }
        if ($mission !== null) {
            $qb = $qb->andWhere('mission.id = :missionId')
                ->setParameter('missionId', $mission->getId())
            ;
        }
        if ($structure !== null) {
            $qb = $qb->andWhere('structure.id = :structureId')
                ->setParameter('structureId', $structure->getId())
            ;
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return AgentMissionSpecifique
     */
    public function getAffectation($id)
    {
        $qb = $this->getEntityManager()->getRepository(AgentMissionSpecifique::class)->createQueryBuilder('affectation')
            ->addSelect('agent')->leftJoin('affectation.agent', 'agent')
            ->addSelect('mission')->leftJoin('affectation.mission', 'mission')
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
            ->andWhere('affectation.id = :id')
            ->setParameter('id', $id)
            ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs AgentMissionSpecifique partagent le même id [".$id."]", $e);
        }
        return $result;

    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return AgentMissionSpecifique
     */
    public function getRequestedAffectation($controller, $paramName='affectation')
    {
        $id = $controller->params()->fromRoute($paramName);
        return $this->getAffectation($id);
    }



    /**
     * @param Structure $structure
     * @param bool $sousstructure
     * @return AgentMissionSpecifique[]
     */
    public function getMissionsSpecifiquesByStructure(Structure $structure, $sousstructure = false)
    {
        $today = $this->getDateTime();

        $qb = $this->getEntityManager()->getRepository(AgentMissionSpecifique::class)->createQueryBuilder('mission')
            ->addSelect('structure')->join('mission.structure', 'structure')
            ->andWhere('mission.structure = :structure OR structure.parent = :structure')
            ->andWhere('mission.dateFin >= :today OR mission.dateFin IS NULL')
            ->setParameter('structure', $structure)
            ->setParameter('today', $today);
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure[] $structures
     * @param boolean $active
     * @return AgentMissionSpecifique[]
     */
    public function getMissionsSpecifiquesByStructures($structures, $active = true)
    {
        $date = $this->getDateTime();
        $qb = $this->createQueryBuilder()
            ->andWhere('affectation.structure IN (:structures)')
            ->setParameter('structures', $structures)
            ->orderBy('agent.nomUsuel, agent.prenom, structure.libelleLong, mission.libelle', 'ASC')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }


}