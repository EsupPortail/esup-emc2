<?php

namespace Application\Service\MissionSpecifiqueTheme;

use Application\Entity\Db\MissionSpecifiqueTheme;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class MissionSpecifiqueThemeService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES ***************************************************************************/

    /**
     * @param MissionSpecifiqueTheme $type
     * @return MissionSpecifiqueTheme
     */
    public function create(MissionSpecifiqueTheme $type) : MissionSpecifiqueTheme
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
     * @param MissionSpecifiqueTheme $type
     * @return MissionSpecifiqueTheme
     */
    public function update(MissionSpecifiqueTheme $type)  : MissionSpecifiqueTheme
    {
        try {
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue dans l'enregistrement en base.",0,$e);
        }
        return $type;
    }

    /**
     * @param MissionSpecifiqueTheme $type
     * @return MissionSpecifiqueTheme
     */
    public function historise(MissionSpecifiqueTheme $type) : MissionSpecifiqueTheme
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
     * @param MissionSpecifiqueTheme $type
     * @return MissionSpecifiqueTheme
     */
    public function restore(MissionSpecifiqueTheme $type) : MissionSpecifiqueTheme
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
     * @param MissionSpecifiqueTheme $type
     * @return MissionSpecifiqueTheme
     */
    public function delete(MissionSpecifiqueTheme $type) : MissionSpecifiqueTheme
    {
        try {
            $this->getEntityManager()->remove($type);
            $this->getEntityManager()->flush($type);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue dans l'enregistrement en base.",0,$e);
        }
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