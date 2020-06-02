<?php

namespace Application\Service\EntretienProfessionnel;

use Application\Entity\Db\EntretienProfessionnelCampagne;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class EntretienProfessionnelCampagneService {
    use UserServiceAwareTrait;
    use DateTimeAwareTrait;
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param EntretienProfessionnelCampagne $campagne
     * @return EntretienProfessionnelCampagne
     */
    public function create(EntretienProfessionnelCampagne $campagne)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();
        $campagne->setHistoCreation($date);
        $campagne->setHistoCreateur($user);
        $campagne->setHistoModification($date);
        $campagne->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($campagne);
            $this->getEntityManager()->flush($campagne);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0, $e);
        }
        return $campagne;
    }

    /**
     * @param EntretienProfessionnelCampagne $campagne
     * @return EntretienProfessionnelCampagne
     */
    public function update(EntretienProfessionnelCampagne $campagne)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();
        $campagne->setHistoModification($date);
        $campagne->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($campagne);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0, $e);
        }
        return $campagne;
    }

    /**
     * @param EntretienProfessionnelCampagne $campagne
     * @return EntretienProfessionnelCampagne
     */
    public function historise(EntretienProfessionnelCampagne $campagne)
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();
        $campagne->setHistoDestruction($date);
        $campagne->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($campagne);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0, $e);
        }
        return $campagne;
    }

    /**
     * @param EntretienProfessionnelCampagne $campagne
     * @return EntretienProfessionnelCampagne
     */
    public function restore(EntretienProfessionnelCampagne $campagne)
    {
        $campagne->setHistoDestruction(null);
        $campagne->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($campagne);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0, $e);
        }
        return $campagne;
    }

    /**
     * @param EntretienProfessionnelCampagne $campagne
     * @return EntretienProfessionnelCampagne
     */
    public function delete(EntretienProfessionnelCampagne $campagne)
    {
        try {
            $this->getEntityManager()->remove($campagne);
            $this->getEntityManager()->flush($campagne);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base.", 0, $e);
        }
        return $campagne;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnelCampagne::class)->createQueryBuilder('campagne')
            ->addSelect('precede')->leftJoin('campagne.precede', 'precede')
            ->addSelect('entretien')->leftJoin('campagne.entretiens', 'entretien');

        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return EntretienProfessionnelCampagne[]
     */
    public function getEntretiensProfessionnelsCampagnes($champ='annee', $ordre='DESC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('campagne.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getEntretiensProfessionnelsCampagnesAsOptions($champ='annee', $ordre='DESC') {
        $campagnes = $this->getEntretiensProfessionnelsCampagnes($champ, $ordre);

        $array = [];
        foreach ($campagnes as $campagne) {
            $array[$campagne->getId()] = $campagne->getAnnee();
        }
        return $array;
    }

    /**
     * @param $id
     * @return EntretienProfessionnelCampagne
     */
    public function getEntretienProfessionnelCampagne($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('campagne.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EntretienProfessionnelCampagne partage le même id [".$id."]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return EntretienProfessionnelCampagne
     */
    public function getRequestedEntretienProfessionnelCampagne($controller, $param = "campagne")
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getEntretienProfessionnelCampagne($id);
        return $result;
    }
}