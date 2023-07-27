<?php

namespace FicheMetier\Service\MissionPrincipale;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use Element\Entity\Db\Competence;
use Element\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Entity\Db\MissionActivite;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class MissionPrincipaleService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES  ******************************************************************************************/

    public function create(Mission $mission) : Mission
    {
        try {
            $this->getEntityManager()->persist($mission);
            $this->getEntityManager()->flush($mission);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est surevnue en BD",0,$e);
        }
        return $mission;
    }

    public function update(Mission $mission) : Mission
    {
        try {
            $this->getEntityManager()->flush($mission);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est surevnue en BD",0,$e);
        }
        return $mission;
    }

    public function historise(Mission $mission) : Mission
    {
        try {
            $mission->historiser();
            $this->getEntityManager()->flush($mission);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est surevnue en BD",0,$e);
        }
        return $mission;
    }

    public function restore(Mission $mission) : Mission
    {
        try {
            $mission->dehistoriser();
            $this->getEntityManager()->flush($mission);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est surevnue en BD",0,$e);
        }
        return $mission;
    }

    public function delete(Mission $mission) : Mission
    {
        try {
            $this->getEntityManager()->remove($mission);
            $this->getEntityManager()->flush($mission);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est surevnue en BD",0,$e);
        }
        return $mission;
    }

    /** QUERRYING *****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Mission::class)->createQueryBuilder('mission')
            ->leftJoin('mission.listeFicheMetierMission', 'listeFicheMetierMission')->addSelect('listeFicheMetierMission')
            ->leftJoin('mission.listeFichePosteMission', 'listeFichePosteMission')->addSelect('listeFichePosteMission')
            ->leftJoin('mission.activites', 'activite')->addSelect('activite')
            ->leftJoin('mission.domaines', 'domaine')->addSelect('domaine')

//            ->leftJoin('mission.applications', 'applicationelement')->addSelect('applicationelement')
//            ->leftJoin('applicationelement.application', 'application')->addSelect('application')
//            ->leftJoin('mission.competences', 'competenceelement')->addSelect('competenceelement')
//            ->leftJoin('competenceelement.competence', 'competence')->addSelect('competence')
        ;
        return $qb;
    }

    /** @return Mission[] */
    public function getMissionsPrincipales(string $champ = 'libelle', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderby('mission.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getMissionPrincipale(?int $id) : ?Mission
    {
        $qb  = $this->createQueryBuilder()
            ->andWhere('mission.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Mission partagent le même id [".$id."]", 0 , $e);
        }
        return $result;
    }

    public function getRequestedMissionPrincipale(AbstractActionController $controller, string $param = 'mission-principale') : ?Mission
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getMissionPrincipale($id);
        return $result;
    }


    public function getMissionsHavingCompetence(?Competence $competence)
    {
        $qb  = $this->createQueryBuilder()
            ->leftJoin('mission.competences', 'competence')->addSelect('competence')
            ->andWhere('competence.id = :competenceId')->setParameter('competenceId', $competence->getId());
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/


    /** @return Mission[] */
    public function findMissionsPrincipalesByExtendedTerm(string $texte) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("LOWER(mission.libelle) like :search or LOWER(activite.libelle) like :search")
            ->andWhere('mission.histoDestruction IS NULL')
            ->andWhere('activite.histoDestruction IS NULL')
            ->setParameter('search', '%'.strtolower($texte).'%');
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function formatToJSON(array $missions) : array
    {
        $result = [];
        /** @var Mission[] $missions */
        foreach ($missions as $mission) {
            $result[] = array(
                'id' => $mission->getId(),
                'label' => $mission->getLibelle(),
//                'description' => 'blabla bli bli',
//                'extra' => "<span class='badge' style='background-color: slategray;'>" .. "</span>",
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }









    /** TODO faire un service dedié */

    public function createActivite(MissionActivite $activite) : MissionActivite
    {
        try {
            $this->getEntityManager()->persist($activite);
            $this->getEntityManager()->flush($activite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue en base");
        }
        return $activite;
    }

    public function getActivite(?int $id) : ?MissionActivite
    {
        if ($id === null) return null;
        return $this->getEntityManager()->getRepository(MissionActivite::class)->find($id);
    }

    public function getRequestedActivite(AbstractActionController $controller, string $param='activite') : ?MissionActivite
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getActivite($id);
    }

    public function ajouterActivite(?Mission $mission, MissionActivite $activite) : MissionActivite
    {
        $activite->setMission($mission);
        $activite->setOrdre(9999);
        try {
            $this->getEntityManager()->persist($activite);
            $this->compressActiviteOrdre($mission);
            $this->getEntityManager()->flush($activite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en BD",0,$e);
        }
        return $activite;
    }

    public function modifierActivite(MissionActivite $activite) : MissionActivite
    {
        try {
            $this->getEntityManager()->flush($activite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en BD",0,$e);
        }
        return $activite;
    }

    public function supprimerActivite(MissionActivite $activite) : MissionActivite
    {
        try {
            $this->getEntityManager()->remove($activite);
            $this->getEntityManager()->flush($activite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en BD",0,$e);
        }
        return $activite;
    }

    public function compressActiviteOrdre(Mission $mission) : Mission
    {
        $activites = $mission->getActivites();
        usort($activites, function (MissionActivite $a, MissionActivite $b) {
            return $a->getOrdre() > $b->getOrdre();
        });

        $position = 1;
        foreach ($activites as $activite) {
            $activite->setOrdre($position);
            try {
                $this->getEntityManager()->flush($activite);
            } catch (ORMException $e) {
                throw new RuntimeException("Un problème est survenu en BD", 0, $e);

            }
            $position++;
        }
        return $mission;
    }



}