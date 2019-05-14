<?php

namespace Application\Service\RessourceRh;

use Application\Entity\Db\Corps;
use Application\Entity\Db\Correspondance;
use Application\Entity\Db\Domaine;
use Application\Entity\Db\Grade;
use Application\Entity\Db\Metier;
use Application\Entity\Db\MetierFamille;
use Application\Entity\Db\MissionSpecifique;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
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
     * @param string $order
     * @return Correspondance[]
     */
    public function getCorrespondances($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(Correspondance::class)->createQueryBuilder('correspondance')
            ->orderBy('correspondance.reference', 'ASC')
        ;

        if ($order !== null) {
            $qb = $qb->addOrderBy('correspondance.'.$order, 'ASC');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
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

    /**
     * @param Correspondance $correspondance
     * @return Correspondance
     */
    public function createCorrespondance($correspondance)
    {
        $this->getEntityManager()->persist($correspondance);
        try {
            $this->getEntityManager()->flush($correspondance);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la création d'une correspondance", $e);
        }
        return $correspondance;
    }

    /**
     * @param Correspondance $correspondance
     * @return Correspondance
     */
    public function updateCorrespondance($correspondance)
    {
        try {
            $this->getEntityManager()->flush($correspondance);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la mise à jour d'une correspondance", $e);
        }
        return $correspondance;
    }

    /**
     * @param Correspondance $correspondance
     */
    public function deleteCorrespondance($correspondance)
    {
        $this->getEntityManager()->remove($correspondance);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la suppression d'une correspondance", $e);
        }
    }

    /** CORPS *********************************************************************************************************/

    /**
     * @param string $order
     * @return Corps[]
     */
    public function getCorpsListe($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(Corps::class)->createQueryBuilder('corps')
            ->addSelect('grade')->leftJoin('corps.grades', 'grade')
        ;

        if ($order !== null) {
            $qb = $qb->addOrderBy('corps.'.$order, 'ASC');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Corps
     */
    public function getCorps($id)
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

    /**
     * @param Corps $corps
     * @return Corps
     */
    public function createCorps($corps)
    {
        $this->getEntityManager()->persist($corps);
        try {
            $this->getEntityManager()->flush($corps);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la création d'un corps", $e);
        }
        return $corps;
    }

    /**
     * @param Corps $corps
     * @return Corps
     */
    public function updateCorps($corps)
    {
        try {
            $this->getEntityManager()->flush($corps);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la mise à jour d'un corps", $e);
        }
        return $corps;
    }

    /**
     * @param Corps $corps
     */
    public function deleteCorps($corps)
    {
        $this->getEntityManager()->remove($corps);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la suppression d'un corps", $e);
        }
    }

    /** CORPS *********************************************************************************************************/

    /**
     * @param string $order
     * @return Metier[]
     */
    public function getMetiers($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(Metier::class)->createQueryBuilder('metier')
        ;

        if ($order !== null) {
            $qb = $qb->addOrderBy('metier.'.$order, 'ASC');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Metier
     */
    public function getMetier($id)
    {
        $qb = $this->getEntityManager()->getRepository(Metier::class)->createQueryBuilder('metier')
            ->andWhere('metier.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs métiers partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function createMetier($metier)
    {
        $this->getEntityManager()->persist($metier);
        try {
            $this->getEntityManager()->flush($metier);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la création d'un métier", $e);
        }
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function updateMetier($metier)
    {
        try {
            $this->getEntityManager()->flush($metier);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la mise à jour d'un metier.", $e);
        }
        return $metier;
    }

    /**
     * @param Metier $metier
     */
    public function deleteMetier($metier)
    {
        $this->getEntityManager()->remove($metier);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la suppression d'un metier", $e);
        }
    }

    /** Famille Metier ************************************************************************************************/

    /**
     * @param string $order
     * @return MetierFamille[]
     */
    public function getMetiersFamilles($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(MetierFamille::class)->createQueryBuilder('famille')
            ->addSelect('metier')->leftJoin('famille.metiers', 'metier')
        ;

        if ($order !== null) {
            $qb = $qb->addOrderBy('famille.'.$order, 'ASC');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return MetierFamille
     */
    public function getMetierFamille($id)
    {
        $qb = $this->getEntityManager()->getRepository(MetierFamille::class)->createQueryBuilder('famille')
            ->andWhere('famille.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs familles de métier partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param MetierFamille $famille
     * @return MetierFamille
     */
    public function createMetierFamille($famille)
    {
        $this->getEntityManager()->persist($famille);
        try {
            $this->getEntityManager()->flush($famille);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la création d'une famille de métier", $e);
        }
        return $famille;
    }

    /**
     * @param MetierFamille $famille
     * @return MetierFamille
     */
    public function updateMetierFamille($famille)
    {
        try {
            $this->getEntityManager()->flush($famille);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la mise à jour d'une famille de metier.", $e);
        }
        return $famille;
    }

    /**
     * @param MetierFamille $famille
     */
    public function deleteMetierFamille($famille)
    {
        $this->getEntityManager()->remove($famille);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la suppression d'une famille de metier", $e);
        }
    }

    /** Domaine *******************************************************************************************************/

    /**
     * @param string $order
     * @return Domaine[]
     */
    public function getDomaines($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(Domaine::class)->createQueryBuilder('domaine')
        ;

        if ($order !== null) {
            $qb = $qb->addOrderBy('domaine.'.$order, 'ASC');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Domaine
     */
    public function getDomaine($id)
    {
        $qb = $this->getEntityManager()->getRepository(Domaine::class)->createQueryBuilder('domaine')
            ->andWhere('domaine.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs domaines partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param Domaine $domaine
     * @return Domaine
     */
    public function createDomaine($domaine)
    {
        $this->getEntityManager()->persist($domaine);
        try {
            $this->getEntityManager()->flush($domaine);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la création d'un Domaine", $e);
        }
        return $domaine;
    }

    /**
     * @param Domaine $domaine
     * @return Domaine
     */
    public function updateDomaine($domaine)
    {
        try {
            $this->getEntityManager()->flush($domaine);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la mise à jour d'un Domaine.", $e);
        }
        return $domaine;
    }

    /**
     * @param Domaine $domaine
     */
    public function deleteDomaine($domaine)
    {
        $this->getEntityManager()->remove($domaine);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la suppression d'un Domaine", $e);
        }
    }

    /** Grade *******************************************************************************************************/

    /**
     * @param string $order
     * @return Grade[]
     */
    public function getGrades($order = null)
    {
        $qb = $this->getEntityManager()->getRepository(Grade::class)->createQueryBuilder('grade')
        ;

        if ($order !== null) {
            $qb = $qb->addOrderBy('grade.'.$order, 'ASC');
        } else {
            $qb = $qb->addOrderBy('grade.corps, grade.rang', 'ASC');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
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

    /**
     * @param Grade $grade
     * @return Grade
     */
    public function createGrade($grade)
    {
        $this->getEntityManager()->persist($grade);
        try {
            $this->getEntityManager()->flush($grade);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la création d'un Grade", $e);
        }
        return $grade;
    }

    /**
     * @param Grade $grade
     * @return Grade
     */
    public function updateGrade($grade)
    {
        try {
            $this->getEntityManager()->flush($grade);
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la mise à jour d'un Grade.", $e);
        }
        return $grade;
    }

    /**
     * @param Grade $grade
     */
    public function deleteGrade($grade)
    {
        $this->getEntityManager()->remove($grade);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la suppression d'un Grade", $e);
        }
    }

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
    public function getRequestedMissionSpecifique($controller, $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        $mission = $this->getMissionSpecifique($id);
        return $mission;
    }

    /**
     * @param MissionSpecifique $mission
     * @return MissionSpecifique
     */
    public function createMissionSpecifique($mission)
    {
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
        try {
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
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
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
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
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
}