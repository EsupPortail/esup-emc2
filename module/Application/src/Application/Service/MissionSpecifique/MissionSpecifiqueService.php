<?php

namespace Application\Service\MissionSpecifique;

use Application\Entity\Db\MissionSpecifique;
use Application\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class MissionSpecifiqueService {
    use EntityManagerAwareTrait;
    use MissionSpecifiqueThemeServiceAwareTrait;

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
    private function createQueryBuilder() : QueryBuilder
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
    public function getMissionsSpecifiques(string $champ = 'theme.libelle', string $ordre='ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy($champ, $ordre)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int|null $id
     * @return MissionSpecifique
     */
    public function getMissionSpecifique(?int $id) : ?MissionSpecifique
    {

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
    public function getRequestedMissionSpecifique(AbstractActionController $controller, string $paramName = 'mission') : ?MissionSpecifique
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
    public function getMisssionsSpecifiquesAsGroupOptions(bool $historiser = false, string $champ = 'libelle', string $ordre = 'ASC') : array
    {
        $themes = $this->getMissionSpecifiqueThemeService()->getMissionsSpecifiquesThemes($historiser, $champ, $ordre);
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
    public function getMisssionsSpecifiquesAsOptions() : array
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
    private function getMissionsSpecifiquesSansTheme() : array
    {
        $qb = $this->getEntityManager()->getRepository(MissionSpecifique::class)->createQueryBuilder('mission')
            ->orderBy('mission.libelle', 'ASC')
            ->andWhere('mission.theme IS NULL')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

}