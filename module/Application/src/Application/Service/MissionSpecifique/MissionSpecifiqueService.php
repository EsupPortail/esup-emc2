<?php

namespace Application\Service\MissionSpecifique;

use Application\Entity\Db\MissionSpecifique;
use Application\Entity\Db\MissionSpecifiqueTheme;
use Application\Entity\Db\MissionSpecifiqueType;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class MissionSpecifiqueService {
    use DateTimeAwareTrait;
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** MISSION SPECIFIQUE ********************************************************************************************/

    /** ENTITY MANAGEMENT **/

    /**
     * @param MissionSpecifique $mission
     * @return MissionSpecifique
     */
    public function create($mission)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

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
    public function update($mission)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

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
    public function historise($mission)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

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
    public function restore($mission)
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
    public function delete($mission)
    {
        try {
            $this->getEntityManager()->remove($mission);
            $this->getEntityManager()->flush($mission);
        } catch (ORMException $e) {
            throw new RuntimeException("Problème lors de la sauvegarde en BD", $e);
        }

        return $mission;
    }

    /** REQUETAGE **/

    /**
     * @return QueryBuilder
     */
    private function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(MissionSpecifique::class)->createQueryBuilder('mission')
            ->addSelect('type')->leftJoin('mission.type', 'type')
            ->addSelect('theme')->leftJoin('mission.theme', 'theme')
            ->addSelect('affectation')->leftJoin('mission.affectations', 'affectation')
            ->addSelect('modificateur')->join('mission.histoModificateur', 'modificateur')
        ;
        return $qb;
    }
    /**
     * @return MissionSpecifique[]
     */
    public function getMissionsSpecifiques() {
        $qb = $this->createQueryBuilder()
            ->orderBy('mission.libelle', 'ASC')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return MissionSpecifique
     */
    public function getMissionSpecifique($id) {
        $qb = $this->createQueryBuilder()
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

    /** MISSION SPECIFIQUE TYPE ***************************************************************************************/

    /** GESTION ENTITES */

    /**
     * @param MissionSpecifiqueType $type
     * @return MissionSpecifiqueType
     */
    public function createType($type)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

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
    public function updateType($type)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

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
    public function historiseType($type)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

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
    public function restoreType($type)
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
    public function deleteType($type)
    {
        try {
            $this->getEntityManager()->remove($type);
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un erreur s'est produite lors de l'enregistrement en BD.", $e);
        }
        return $type;
    }

    /** REQUETAGE */

    /**
     * @param bool $historiser
     * @param string $champ
     * @param string $ordre
     * @return MissionSpecifiqueType[]
     */
    public function getMissionsSpecifiquesTypes($historiser= true, $champ = 'libelle', $ordre ='ASC')
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
    public function getRequestedMissionSpecifiqueType($controller, $paramName = 'type')
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getMissionSpecifiqueType($id);
        return $result;
    }



    /** MISSION SPECIFIQUE THEME **************************************************************************************/

    /** GESTION ENTITES */

    /**
     * @param MissionSpecifiqueTheme $theme
     * @return MissionSpecifiqueTheme
     */
    public function createTheme($theme)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $theme->setHistoCreateur($user);
        $theme->setHistoCreation($date);
        $theme->setHistoModificateur($user);
        $theme->setHistoModification($date);

        try {
            $this->getEntityManager()->persist($theme);
            $this->getEntityManager()->flush($theme);
        } catch (ORMException $e) {
            throw new RuntimeException("Un erreur s'est produite lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /**
     * @param MissionSpecifiqueTheme $theme
     * @return MissionSpecifiqueTheme
     */
    public function updateTheme($theme)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $theme->setHistoModificateur($user);
        $theme->setHistoModification($date);

        try {
            $this->getEntityManager()->flush($theme);
        } catch (ORMException $e) {
            throw new RuntimeException("Un erreur s'est produite lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /**
     * @param MissionSpecifiqueTheme $theme
     * @return MissionSpecifiqueTheme
     */
    public function historiseTheme($theme)
    {
        $date = $this->getDateTime();
        $user = $this->getUserService()->getConnectedUser();

        $theme->setHistoDestructeur($user);
        $theme->setHistoDestruction($date);

        try {
            $this->getEntityManager()->flush($theme);
        } catch (ORMException $e) {
            throw new RuntimeException("Un erreur s'est produite lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /**
     * @param MissionSpecifiqueTheme $theme
     * @return MissionSpecifiqueTheme
     */
    public function restoreTheme($theme)
    {
        $theme->setHistoDestructeur(null);
        $theme->setHistoDestruction(null);

        try {
            $this->getEntityManager()->flush($theme);
        } catch (ORMException $e) {
            throw new RuntimeException("Un erreur s'est produite lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /**
     * @param MissionSpecifiqueTheme $theme
     * @return MissionSpecifiqueTheme
     */
    public function deleteTheme($theme)
    {
        try {
            $this->getEntityManager()->remove($theme);
            $this->getEntityManager()->flush($theme);
        } catch (ORMException $e) {
            throw new RuntimeException("Un erreur s'est produite lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /** REQUETAGES */
    /**
     * @param bool $historiser
     * @param string $champ
     * @param string $ordre
     * @return MissionSpecifiqueTheme[]
     */
    public function getMissionsSpecifiquesThemes($historiser= true, $champ = 'libelle', $ordre ='ASC')
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
    public function getRequestedMissionSpecifiqueTheme($controller, $paramName = 'theme')
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getMissionSpecifiqueTheme($id);
        return $result;
    }

}