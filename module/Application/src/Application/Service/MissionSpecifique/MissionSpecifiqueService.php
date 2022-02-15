<?php

namespace Application\Service\MissionSpecifique;

use Application\Entity\Db\MissionSpecifique;
use Application\Entity\Db\MissionSpecifiqueTheme;
use Application\Entity\Db\MissionSpecifiqueType;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class MissionSpecifiqueService {
    use EntityManagerAwareTrait;

    /** MISSION SPECIFIQUE ********************************************************************************************/

    /**
     * @param MissionSpecifique $mission
     * @return MissionSpecifique
     */
    public function create(MissionSpecifique $mission) : MissionSpecifique
    {
        try {
            $this->getEntityManager()->persist($mission);
            $this->getEntityManager()->flush($mission);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $mission;
    }

    /**
     * @param MissionSpecifique $mission
     * @return MissionSpecifique
     */
    public function update(MissionSpecifique $mission) : MissionSpecifique
    {
        try {
            $this->getEntityManager()->flush($mission);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $mission;
    }

    /**
     * @param MissionSpecifique $mission
     * @return MissionSpecifique
     */
    public function historise(MissionSpecifique $mission) : MissionSpecifique
    {
        try {
            $mission->historiser();
            $this->getEntityManager()->flush($mission);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $mission;
    }

    /**
     * @param MissionSpecifique $mission
     * @return MissionSpecifique
     */
    public function restore(MissionSpecifique $mission) : MissionSpecifique
    {
        try {
            $mission->dehistoriser();
            $this->getEntityManager()->flush($mission);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $mission;
    }

    /**
     * @param MissionSpecifique $mission
     * @return MissionSpecifique
     */
    public function delete(MissionSpecifique $mission) : MissionSpecifique
    {
        try {
            $this->getEntityManager()->remove($mission);
            $this->getEntityManager()->flush($mission);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $mission;
    }

    /** REQUETAGE *****************************************************************************************************/

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
     * @param string $champ
     * @param string $ordre
     * @return MissionSpecifique[]
     */
    public function getMissionsSpecifiques(string $champ = 'theme.libelle', string $ordre='ASC') {
        $qb = $this->createQueryBuilder()
            ->orderBy($champ, $ordre)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return MissionSpecifique
     */
    public function getMissionSpecifique($id) {

        if ($id === null) return null;
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
    public function getRequestedMissionSpecifique(AbstractActionController $controller, $paramName = 'mission')
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
     * @return array
     */
    public function getMisssionsSpecifiquesAsOptions()
    {
        $missions = $this->getMissionsSpecifiques();
        $array = [];
        foreach ($missions as $mission) {
            $array[$mission->getId()] = $mission->getLibelle();
        }
        return $array;
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
    public function createType(MissionSpecifiqueType $type)
    {
        $this->createFromTrait($type);
        return $type;
    }

    /**
     * @param MissionSpecifiqueType $type
     * @return MissionSpecifiqueType
     */
    public function updateType(MissionSpecifiqueType $type)
    {
        $this->updateFromTrait($type);
        return $type;
    }

    /**
     * @param MissionSpecifiqueType $type
     * @return MissionSpecifiqueType
     */
    public function historiseType(MissionSpecifiqueType $type)
    {
        $this->historiserFromTrait($type);
        return $type;
    }

    /**
     * @param MissionSpecifiqueType $type
     * @return MissionSpecifiqueType
     */
    public function restoreType(MissionSpecifiqueType $type)
    {
        $this->restoreFromTrait($type);
        return $type;
    }

    /**
     * @param MissionSpecifiqueType $type
     * @return MissionSpecifiqueType
     */
    public function deleteType(MissionSpecifiqueType $type)
    {
        $this->deleteFromTrait($type);
        return $type;
    }

    /** REQUETAGE *****************************************************************************************************/

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
    public function getMissionSpecifiqueType(?int $id)
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
    public function getRequestedMissionSpecifiqueType(AbstractActionController $controller, $paramName = 'type')
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
    public function createTheme(MissionSpecifiqueTheme $theme)
    {
        $this->createFromTrait($theme);
        return $theme;
    }

    /**
     * @param MissionSpecifiqueTheme $theme
     * @return MissionSpecifiqueTheme
     */
    public function updateTheme(MissionSpecifiqueTheme $theme)
    {
        $this->updateFromTrait($theme);
        return $theme;
    }

    /**
     * @param MissionSpecifiqueTheme $theme
     * @return MissionSpecifiqueTheme
     */
    public function historiseTheme(MissionSpecifiqueTheme $theme)
    {
        $this->historiserFromTrait($theme);
        return $theme;
    }

    /**
     * @param MissionSpecifiqueTheme $theme
     * @return MissionSpecifiqueTheme
     */
    public function restoreTheme(MissionSpecifiqueTheme $theme)
    {
        $this->restoreFromTrait($theme);
        return $theme;
    }

    /**
     * @param MissionSpecifiqueTheme $theme
     * @return MissionSpecifiqueTheme
     */
    public function deleteTheme(MissionSpecifiqueTheme $theme)
    {
        $this->deleteFromTrait($theme);
        return $theme;
    }

    /** REQUETAGES */
    /**
     * @param bool $historiser
     * @param string $champ
     * @param string $ordre
     * @return MissionSpecifiqueTheme[]
     */
    public function getMissionsSpecifiquesThemes(bool $historiser= true, string $champ = 'libelle', string $ordre ='ASC') : array
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
    public function getMissionsSpecifiquesThemesAsOptions(bool $historiser= false, string $champ = 'libelle', string $ordre ='ASC') : array
    {
        $types = $this->getMissionsSpecifiquesThemes($historiser, $champ, $ordre);
        $array = [];
        foreach ($types as $type) {
            $array[$type->getId()] = $type->getLibelle();
        }
        return $array;
    }

    /**
     * @param int|null $id
     * @return MissionSpecifiqueTheme|null
     */
    public function getMissionSpecifiqueTheme(?int $id) : ?MissionSpecifiqueTheme
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
     * @return MissionSpecifiqueTheme|null
     */
    public function getRequestedMissionSpecifiqueTheme(AbstractActionController $controller, string $paramName = 'theme') : ?MissionSpecifiqueTheme
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getMissionSpecifiqueTheme($id);
        return $result;
    }
}