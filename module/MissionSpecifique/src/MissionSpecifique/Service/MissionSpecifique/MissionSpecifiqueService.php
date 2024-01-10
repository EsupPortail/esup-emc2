<?php

namespace MissionSpecifique\Service\MissionSpecifique;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use MissionSpecifique\Entity\Db\MissionSpecifique;
use MissionSpecifique\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeServiceAwareTrait;
use RuntimeException;

class MissionSpecifiqueService
{
    use ProvidesObjectManager;
    use MissionSpecifiqueThemeServiceAwareTrait;

    /** MISSION SPECIFIQUE ********************************************************************************************/

    public function create(MissionSpecifique $mission): MissionSpecifique
    {
        $this->getObjectManager()->persist($mission);
        $this->getObjectManager()->flush($mission);
        return $mission;
    }

    public function update(MissionSpecifique $mission): MissionSpecifique
    {
        $this->getObjectManager()->flush($mission);
        return $mission;
    }

    public function historise(MissionSpecifique $mission): MissionSpecifique
    {
        $mission->historiser();
        $this->getObjectManager()->flush($mission);
        return $mission;
    }

    public function restore(MissionSpecifique $mission): MissionSpecifique
    {
        $mission->dehistoriser();
        $this->getObjectManager()->flush($mission);
        return $mission;
    }

    public function delete(MissionSpecifique $mission): MissionSpecifique
    {
        $this->getObjectManager()->remove($mission);
        $this->getObjectManager()->flush($mission);
        return $mission;
    }

    /** REQUETAGE *****************************************************************************************************/

    private function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(MissionSpecifique::class)->createQueryBuilder('mission')
            ->addSelect('type')->leftJoin('mission.type', 'type')
            ->addSelect('theme')->leftJoin('mission.theme', 'theme')
            ->addSelect('affectation')->leftJoin('mission.affectations', 'affectation')
            ->addSelect('modificateur')->join('mission.histoModificateur', 'modificateur');
        return $qb;
    }

    /* @return MissionSpecifique[] */
    public function getMissionsSpecifiques(string $champ = 'theme.libelle', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy($champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getMissionSpecifique(?int $id): ?MissionSpecifique
    {

        if ($id === null) return null;
        $qb = $this->createQueryBuilder()
            ->andWhere('mission.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs MissionSpecifique partagent le même id [" . $id . "]", $e);
        }
        return $result;
    }

    public function getRequestedMissionSpecifique(AbstractActionController $controller, string $paramName = 'mission'): ?MissionSpecifique
    {
        $id = $controller->params()->fromRoute($paramName);
        $mission = $this->getMissionSpecifique($id);
        return $mission;
    }

    /* @return MissionSpecifique[] */
    public function getMisssionsSpecifiquesAsGroupOptions(bool $historiser = false, string $champ = 'libelle', string $ordre = 'ASC'): array
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

    /* @return string[] */
    public function getMisssionsSpecifiquesAsOptions(): array
    {
        $missions = $this->getMissionsSpecifiques();
        $array = [];
        foreach ($missions as $mission) {
            $array[$mission->getId()] = $mission->getLibelle();
        }
        return $array;
    }

    /** @return MissionSpecifique[] */
    private function getMissionsSpecifiquesSansTheme(): array
    {
        $qb = $this->getObjectManager()->getRepository(MissionSpecifique::class)->createQueryBuilder('mission')
            ->orderBy('mission.libelle', 'ASC')
            ->andWhere('mission.theme IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

}