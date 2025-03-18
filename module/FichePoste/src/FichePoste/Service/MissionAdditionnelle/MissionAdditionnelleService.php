<?php

namespace FichePoste\Service\MissionAdditionnelle;

use Application\Entity\Db\FichePoste;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\Mission;
use FichePoste\Entity\Db\MissionAdditionnelle;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class MissionAdditionnelleService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(MissionAdditionnelle $missionAdditionnelle): MissionAdditionnelle
    {
        $this->getObjectManager()->persist($missionAdditionnelle);
        $this->getObjectManager()->flush($missionAdditionnelle);
        return $missionAdditionnelle;
    }

    public function update(MissionAdditionnelle $missionAdditionnelle): MissionAdditionnelle
    {
        $this->getObjectManager()->flush($missionAdditionnelle);
        return $missionAdditionnelle;
    }

    public function historise(MissionAdditionnelle $missionAdditionnelle): MissionAdditionnelle
    {
        $missionAdditionnelle->historiser();
        $this->getObjectManager()->flush($missionAdditionnelle);
        return $missionAdditionnelle;
    }

    public function restore(MissionAdditionnelle $missionAdditionnelle): MissionAdditionnelle
    {
        $missionAdditionnelle->dehistoriser();
        $this->getObjectManager()->flush($missionAdditionnelle);
        return $missionAdditionnelle;
    }

    public function delete(MissionAdditionnelle $missionAdditionnelle): MissionAdditionnelle
    {
        $this->getObjectManager()->remove($missionAdditionnelle);
        $this->getObjectManager()->flush($missionAdditionnelle);
        return $missionAdditionnelle;
    }

    /** REQUETAGES ****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(MissionAdditionnelle::class)->createQueryBuilder('missionadditionnelle')
            ->join('missionadditionnelle.ficheposte', 'ficheposte')->addSelect('ficheposte')
            ->join('missionadditionnelle.mission', 'mission')->addSelect('mission');
        return $qb;
    }

    public function getMissionAdditionnelle(?int $id): ?MissionAdditionnelle
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('missionadditionnelle.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . MissionAdditionnelle::class . "] partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedMissionAdditionnelle(AbstractActionController $controller, string $param = 'mission-additionnelle'): ?MissionAdditionnelle
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getMissionAdditionnelle($id);
    }

    /** @return MissionAdditionnelle[] */
    public function getMissionsAdditionnelles(bool $histo = false, string $champ = 'ficheposte', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('missionadditionnelle.' . $champ, $ordre);
        if (!$histo) {
            $qb = $qb->andWhere('missionadditionnelle.histoDestruction IS NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return MissionAdditionnelle[] */
    public function getMissionsAdditionnellesByFichePoste(FichePoste $fiche, bool $histo = false, string $champ = 'ficheposte', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('missionadditionnelle.ficheposte = :fiche')->setParameter('fiche', $fiche)
            ->orderBy('missionadditionnelle.' . $champ, $ordre);
        if (!$histo) {
            $qb = $qb->andWhere('missionadditionnelle.histoDestruction IS NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function ajouterMissionAdditionnelle(FichePoste $fichePoste, Mission $mission): MissionAdditionnelle
    {
        $missionaddtionnelle = new MissionAdditionnelle();
        $missionaddtionnelle->setFicheposte($fichePoste);
        $missionaddtionnelle->setMission($mission);

        $this->create($missionaddtionnelle);
        return $missionaddtionnelle;
    }

}