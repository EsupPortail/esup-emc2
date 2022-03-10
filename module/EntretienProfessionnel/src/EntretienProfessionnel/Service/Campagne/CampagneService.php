<?php

namespace EntretienProfessionnel\Service\Campagne;

use Application\Entity\Db\Structure;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class CampagneService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Campagne $campagne
     * @return Campagne
     */
    public function create(Campagne $campagne) : Campagne
    {
        try {
            $this->getEntityManager()->persist($campagne);
            $this->getEntityManager()->flush($campagne);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $campagne;
    }

    /**
     * @param Campagne $campagne
     * @return Campagne
     */
    public function update(Campagne $campagne) : Campagne
    {
        try {
            $this->getEntityManager()->flush($campagne);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $campagne;
    }

    /**
     * @param Campagne $campagne
     * @return Campagne
     */
    public function historise(Campagne $campagne) : Campagne
    {
        try {
            $campagne->historiser();
            $this->getEntityManager()->flush($campagne);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $campagne;
    }

    /**
     * @param Campagne $campagne
     * @return Campagne
     */
    public function restore(Campagne $campagne) : Campagne
    {
        try {
            $campagne->dehistoriser();
            $this->getEntityManager()->flush($campagne);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $campagne;
    }

    /**
     * @param Campagne $campagne
     * @return Campagne
     */
    public function delete(Campagne $campagne) : Campagne
    {
        try {
            $this->getEntityManager()->remove($campagne);
            $this->getEntityManager()->flush($campagne);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $campagne;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Campagne::class)->createQueryBuilder('campagne')
            ->addSelect('precede')->leftJoin('campagne.precede', 'precede')
            ->addSelect('entretien')->leftJoin('campagne.entretiens', 'entretien')
            ->addSelect('sursis')->leftJoin('entretien.sursis', 'sursis')
            ->addSelect('etat')->leftJoin('entretien.etat', 'etat')
            ->addSelect('etattype')->leftJoin('etat.type', 'etattype')
        ;

        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Campagne[]
     */
    public function getCampagnes(string $champ='annee', string $ordre='DESC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('campagne.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return array (id => string)
     */
    public function getCampagnesAsOptions(string $champ='annee', string $ordre='DESC') : array
    {
        $campagnes = $this->getCampagnes($champ, $ordre);

        $array = [];
        foreach ($campagnes as $campagne) {
            $array[$campagne->getId()] = $campagne->getAnnee();
        }
        return $array;
    }

    /**
     * @param int|null $id
     * @return Campagne
     */
    public function getCampagne(?int $id) : ?Campagne
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
     * @return Campagne|null
     */
    public function getRequestedCampagne(AbstractActionController $controller, string $param = "campagne") : ?Campagne
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCampagne($id);
        return $result;
    }

    /**
     * @param DateTime|null $date
     * @return Campagne[]
     */
    public function getCampagnesActives(?DateTime $date = null) : array
    {
        $qb = $this->createQueryBuilder();
        $qb = Campagne::decorateWithActif($qb, 'campagne', $date);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param DateTime|null $date
     * @return Campagne|null
     */
    public function getLastCampagne(?DateTime $date = null) : ?Campagne
    {
        if ($date === null) $date = new DateTime();
        $qb = $this->createQueryBuilder()
            ->andWhere('campagne.dateFin < :date')
            ->setParameter('date', $date)
        ;
        $result = $qb->getQuery()->getResult();

        $last = null;
        /** @var Campagne $item */
        foreach ($result as $item) {
            if ($last === null OR $item->getAnnee() > $last->getAnnee()) $last = $item;
        }
        return $item;
    }

    public function getAgentsSansEntretien(Campagne $campagne, Structure $structure)
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->join('entretien.agent', 'agent')->addSelect('agent')
            ->join('agent.affectations', 'affectation')->addSelect('affectation')
            ->andWhere('entretien.campagne = :campagne')
            ->andWhere('affectation.structure = :structure')
            ->andWhere('affectation.dateDebut IS NULL OR affectation.dateDebut <= :now')
            ->andWhere('affectation.dateFin IS NULL OR affectation.dateFin >= :now')
            ->setParameter('campagne', $campagne)
            ->setParameter('structure', $structure)
            ->setParameter('now', new DateTime())
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getAgentsAvecEntretiensEnCours(Campagne $campagne, array $agents)
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->join('entretien.agent', 'agent')->addSelect('agent')
            ->join('entretien.etat','etat')->addSelect('etat')
            ->andWhere('etat.code <> :code')
            ->andWhere('entretien.campagne = :campagne')
            ->andWhere('agent in (:agents)')
            ->setParameter('code', EntretienProfessionnel::ETAT_VALIDATION_HIERARCHIE)
            ->setParameter('campagne', $campagne)
            ->setParameter('agents', $agents)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getAgentsAvecEntretiensFinalises(Campagne $campagne, array $agents)
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->join('entretien.agent', 'agent')->addSelect('agent')
            ->join('entretien.etat','etat')->addSelect('etat')
            ->andWhere('etat.code = :code')
            ->andWhere('entretien.campagne = :campagne')
            ->andWhere('agent in (:agents)')
            ->setParameter('code', EntretienProfessionnel::ETAT_VALIDATION_HIERARCHIE)
            ->setParameter('campagne', $campagne)
            ->setParameter('agents', $agents)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public static function getAnneeScolaire() : string
    {
        $date = new DateTime();
        $annee = ((int) $date->format("Y"));
        $mois  = ((int) $date->format("m"));

        if ($mois < 9) {
            $scolaire = ($annee - 1) . "/" . ($annee);
        } else {
            $scolaire = ($annee) . "/" . ($annee + 1);
        }
        return $scolaire;
    }
}