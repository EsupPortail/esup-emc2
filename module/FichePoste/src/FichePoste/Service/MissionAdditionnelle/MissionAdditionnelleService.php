<?php

namespace FichePoste\Service\MissionAdditionnelle;

use Application\Entity\Db\FichePoste;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use FicheMetier\Entity\Db\Mission;
use FichePoste\Entity\Db\MissionAdditionnelle;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class MissionAdditionnelleService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(MissionAdditionnelle $missionAdditionnelle) : MissionAdditionnelle
    {
        try {
            $this->getEntityManager()->persist($missionAdditionnelle);
            $this->getEntityManager()->flush($missionAdditionnelle);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $missionAdditionnelle;
    }

    public function update(MissionAdditionnelle $missionAdditionnelle) : MissionAdditionnelle
    {
        try {
            $this->getEntityManager()->flush($missionAdditionnelle);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $missionAdditionnelle;
    }

    public function historise(MissionAdditionnelle $missionAdditionnelle) : MissionAdditionnelle
    {
        try {
            $missionAdditionnelle->historiser();
            $this->getEntityManager()->flush($missionAdditionnelle);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $missionAdditionnelle;
    }

    public function restore(MissionAdditionnelle $missionAdditionnelle) : MissionAdditionnelle
    {
        try {
            $missionAdditionnelle->dehistoriser();
            $this->getEntityManager()->flush($missionAdditionnelle);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $missionAdditionnelle;
    }

    public function delete(MissionAdditionnelle $missionAdditionnelle) : MissionAdditionnelle
    {
        try {
            $this->getEntityManager()->remove($missionAdditionnelle);
            $this->getEntityManager()->flush($missionAdditionnelle);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $missionAdditionnelle;
    }

    /** REQUETAGES ****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(MissionAdditionnelle::class)->createQueryBuilder('missionadditionnelle')
            ->join('missionadditionnelle.ficheposte', 'ficheposte')->addSelect('ficheposte')
            ->join('missionadditionnelle.mission', 'mission')->addSelect('mission')
            ;
        return $qb;
    }

    public function getMissionAdditionnelle(?int $id) : ?MissionAdditionnelle
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('missionadditionnelle.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".MissionAdditionnelle::class."] partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedMissionAdditionnelle(AbstractActionController $controller, string $param='mission-additionnelle')
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getMissionAdditionnelle($id);
    }

    /** @return MissionAdditionnelle[] */
    public function getMissionsAdditionnelles(bool $histo = false, string $champ = 'ficheposte', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('missionadditionnelle.'.$champ, $ordre);
        if (!$histo) {
            $qb = $qb->andWhere('missionadditionnelle.histoDestruction IS NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return MissionAdditionnelle[] */
    public function getMissionsAdditionnellesByFichePoste(FichePoste $fiche, bool $histo = false, string $champ = 'ficheposte', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('missionadditionnelle.ficheposte = :fiche')->setParameter('fiche', $fiche)
            ->orderBy('missionadditionnelle.'.$champ, $ordre);
        if (!$histo) {
            $qb = $qb->andWhere('missionadditionnelle.histoDestruction IS NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function ajouterMissionAdditionnelle(FichePoste $fichePoste, Mission $mission) :  MissionAdditionnelle
    {
        $missionaddtionnelle = new MissionAdditionnelle();
        $missionaddtionnelle->setFicheposte($fichePoste);
        $missionaddtionnelle->setMission($mission);

        $this->create($missionaddtionnelle);
        return $missionaddtionnelle;
    }

}