<?php

namespace MissionSpecifique\Service\MissionSpecifiqueTheme;

use Doctrine\ORM\NonUniqueResultException;
use DoctrineModule\Persistence\ProvidesObjectManager;
use MissionSpecifique\Entity\Db\MissionSpecifiqueTheme;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class MissionSpecifiqueThemeService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITES ***************************************************************************/

    public function create(MissionSpecifiqueTheme $type) : MissionSpecifiqueTheme
    {
        $this->getObjectManager()->persist($type);
        $this->getObjectManager()->flush($type);
        return $type;
    }

    public function update(MissionSpecifiqueTheme $type)  : MissionSpecifiqueTheme
    {
        $this->getObjectManager()->flush($type);
        return $type;
    }

    public function historise(MissionSpecifiqueTheme $type) : MissionSpecifiqueTheme
    {
        $type->historiser();
        $this->getObjectManager()->flush($type);
        return $type;
    }

    public function restore(MissionSpecifiqueTheme $type) : MissionSpecifiqueTheme
    {
        $type->dehistoriser();
        $this->getObjectManager()->flush($type);
        return $type;
    }

    public function delete(MissionSpecifiqueTheme $type) : MissionSpecifiqueTheme
    {
        $this->getObjectManager()->remove($type);
        $this->getObjectManager()->flush($type);
        return $type;
    }

    /** REQUETAGE *************************************************************************************/

    /**
     * @param bool $historiser
     * @param string $champ
     * @param string $ordre
     * @return MissionSpecifiqueTheme[]
     */
    public function getMissionsSpecifiquesThemes(bool $historiser= true, string $champ = 'libelle', string $ordre ='ASC') : array
    {
        $qb = $this->getObjectManager()->getRepository(MissionSpecifiqueTheme::class)->createQueryBuilder('theme')
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

    /** @return string[] */
    public function getMissionsSpecifiquesThemesAsOptions(bool $historiser= false, string $champ = 'libelle', string $ordre ='ASC') : array
    {
        $types = $this->getMissionsSpecifiquesThemes($historiser, $champ, $ordre);
        $array = [];
        foreach ($types as $type) {
            $array[$type->getId()] = $type->getLibelle();
        }
        return $array;
    }

    public function getMissionSpecifiqueTheme(?int $id) : ?MissionSpecifiqueTheme
    {
        $qb = $this->getObjectManager()->getRepository(MissionSpecifiqueTheme::class)->createQueryBuilder('type')
            ->andWhere('type.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
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