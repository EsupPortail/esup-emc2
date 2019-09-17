<?php

namespace Application\Service\RessourceRh;

use Application\Entity\Db\Corps;
use Application\Entity\Db\Correspondance;
use Application\Entity\Db\Grade;
use Application\Entity\Db\Metier;
use Application\Entity\Db\MissionSpecifique;
use Application\Entity\Db\MissionSpecifiqueTheme;
use Application\Entity\Db\MissionSpecifiqueType;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenAuth\Entity\Db\User;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class RessourceRhService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** CORRESPONDANCE ************************************************************************************************/

    /**
     * @param bool $active
     * @param string $order
     * @return Correspondance[]
     */
    public function getCorrespondances($active = null, $order = null)
    {
        $qb = $this->getEntityManager()->getRepository(Correspondance::class)->createQueryBuilder('correspondance')
            ->orderBy('correspondance.libelleCourt', 'ASC')
        ;
        if ($active !== null) {
            if ($active)    $qb = $qb ->andWhere("correspondance.histo = 'O'");
            else            $qb = $qb ->andWhere("correspondance.histo <> 'O'");
        }

        if ($order !== null) {
            $qb = $qb->addOrderBy('correspondance.'.$order, 'ASC');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getCorrespondancesAsOptions()
    {
        $correspondances = $this->getCorrespondances(true);

        $array = [];
        foreach ($correspondances as $correspondance) {
            $array[$correspondance->getId()] = $correspondance->getLibelleLong() . " - " . $correspondance->getLibelleLong();
        }

        return $array;
    }

    /**
     * @param string $id
     * @return Correspondance
     */
    public function getCorrespondance($id)
    {
        $qb = $this->getEntityManager()->getRepository(Correspondance::class)->createQueryBuilder('correspondance')
            ->andWhere('correspondance.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs correpondances partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /** Grade *******************************************************************************************************/

    /**
     * @param bool $active
     * @param string $order
     * @return Correspondance[]
     */
    public function getGrades($active = null, $order = null)
    {
        $qb = $this->getEntityManager()->getRepository(Grade::class)->createQueryBuilder('grade')
            ->orderBy('grade.libelleCourt', 'ASC')
        ;
        if ($active !== null) {
            if ($active)    $qb = $qb ->andWhere("grade.histo = 'O'");
            else            $qb = $qb ->andWhere("grade.histo <> 'O'");
        }

        if ($order !== null) {
            $qb = $qb->addOrderBy('grade.'.$order, 'ASC');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getGradesAsOptions()
    {
        $grades = $this->getGrades(true);

        $array = [];
        foreach ($grades as $grade) {
            $array[$grade->getId()] = $grade->getLibelleCourt() . " - ". $grade->getLibelleLong();
        }

        return $array;
    }

    /**
     * @param integer $id
     * @return Grade
     */
    public function getGrade($id)
    {
        $qb = $this->getEntityManager()->getRepository(Grade::class)->createQueryBuilder('grade')
            ->andWhere('grade.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs grades partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /** Corps *********************************************************************************************************/

    /**
     * @param bool $active
     * @param string $order
     * @return Correspondance[]
     */
    public function getCorps($active = null, $order = null)
    {
        $qb = $this->getEntityManager()->getRepository(Corps::class)->createQueryBuilder('corps')
            ->orderBy('corps.libelleCourt', 'ASC')
        ;
        if ($active !== null) {
            if ($active)    $qb = $qb ->andWhere("corps.histo = 'O'");
            else            $qb = $qb ->andWhere("corps.histo <> 'O'");
        }

        if ($order !== null) {
            $qb = $qb->addOrderBy('corps.'.$order, 'ASC');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getCorpsAsOptions()
    {
        $corps = $this->getCorps(true);

        $array = [];
        foreach ($corps as $item) {
            $array[$item->getId()] = $item->getLibelleCourt() . " - " . $item->getLibelleLong() ;
        }
        return $array;
    }

    /**
     * @param integer $id
     * @return Corps
     */
    public function getCorp($id)
    {
        $qb = $this->getEntityManager()->getRepository(Corps::class)->createQueryBuilder('corps')
            ->andWhere('corps.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs corps partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /** ***********************************************/

    public function getMetiersTypesAsOptions()
    {
        $qb = $this->getEntityManager()->getRepository(Metier::class)->createQueryBuilder('metier')
//            ->andWhere('fiche.histoDestruction IS NULL')
            ->orderBy('metier.libelle', 'ASC');

        $result = $qb->getQuery()->getResult();

        $options = [];
        /** @var Metier $metier */
        foreach ($result as $metier) {
            $options[$metier->getId()] = $metier->getLibelle();
        }
        return $options;
    }


    public function getMetiersTypesAsMultiOptions()
    {
        /** @var Metier[] $metiers */
        $qb = $this->getEntityManager()->getRepository(Metier::class)->createQueryBuilder('metier')
        ->orderBy('metier.libelle', 'ASC');
        $metiers = $qb->getQuery()->getResult();

        $vide = [];
        $result = [];
        foreach ($metiers as $metier) {
            if ($metier->getDomaine()) {
                $result[$metier->getDomaine()->getLibelle()][] = $metier;
            } else {
                $vide[] = $metier;
            }
        }
        ksort($result);
        $multi = [];
        foreach ($result as $key => $metiers) {
            //['label'=>'A', 'options' => ["A" => "A", "a"=> "a"]],
            $options = [];
            foreach ($metiers as $metier) {
                $options[$metier->getId()] = $metier->getLibelle();
            }
            $multi[] = ['label' => $key, 'options' => $options];
        }
        $options = [];
        foreach ($vide as $metier) {
            $options[$metier->getId()] = $metier->getLibelle();
        }
        $multi[] = ['label' => 'Sans domaine rattaché', 'options' => $options];
        return $multi;

    }
    /** MISSION SPECIFIQUE ********************************************************************************************/

    /**
     * @return MissionSpecifique[]
     */
    public function getMissionsSpecifiques() {
        $qb = $this->getEntityManager()->getRepository(MissionSpecifique::class)->createQueryBuilder('mission')
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

        $this->getEntityManager()->persist($mission);
        try {
            $this->getEntityManager()->flush($mission);
        } catch (OptimisticLockException $e) {
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
        } catch (OptimisticLockException $e) {
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
        } catch (OptimisticLockException $e) {
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
        } catch (OptimisticLockException $e) {
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

        $this->getEntityManager()->remove($mission);
        try {
            $this->getEntityManager()->flush($mission);
        } catch (OptimisticLockException $e) {
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
        $qb = $this->getEntityManager()->getRepository(MissionSpecifiqueTheme::class)->createQueryBuilder('type')
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

    /** CARTOGRAPHIE **************************************************************************************************/

    /**
     * @return Metier[]
     */
    public function getCartographie()
    {
        $qb = $this->getEntityManager()->getRepository(Metier::class)->createQueryBuilder('metier')
            ->addSelect('famille')->join('metier.famille', 'famille')
            ->addSelect('domaine')->join('metier.domaine', 'domaine')
            ->orderBy('famille.libelle, domaine.libelle, metier.libelle');

        $result = $qb->getQuery()->getResult();
        return $result;
    }


}