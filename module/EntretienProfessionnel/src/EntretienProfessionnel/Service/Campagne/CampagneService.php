<?php

namespace EntretienProfessionnel\Service\Campagne;

use Application\Entity\Db\Agent;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateInterval;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use Structure\Entity\Db\Structure;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenEtat\Entity\Db\Etat;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use EntretienProfessionnel\Provider\Parametre\EntretienProfessionnelParametres;


class CampagneService {
    use EntityManagerAwareTrait;
    use AgentServiceAwareTrait;
    use EtatServiceAwareTrait;
	use ParametreServiceAwareTrait;

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
        return $last;
    }

    /**
     * @param Campagne $campagne
     * @param Etat[] $etats
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensByCampagneAndEtats(Campagne $campagne, array $etats) : array
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->andWhere('entretien.campagne in (:campagne)')->setParameter('campagne', $campagne)
            ->andWhere('entretien.etat in (:etats)')->setParameter('etats', $etats)
            ->andWhere('entretien.histoDestruction IS NULL')
        ;
        $result = $qb->getQuery()->getResult();

        $entretiens = [];
        /** @var EntretienProfessionnel $entretien */
        foreach ($result as $entretien) {
            $entretiens[$entretien->getAgent()->getId()][] = $entretien;
        }
        return $entretiens;
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
            ->setParameter('code', EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_HIERARCHIE)
            ->setParameter('campagne', $campagne)
            ->setParameter('agents', $agents)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getAgentsAvecEntretiensPlanifies(Campagne $campagne, array $agents)
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->join('entretien.agent', 'agent')->addSelect('agent')
            ->join('entretien.etat','etat')->addSelect('etat')
            ->andWhere('etat.code = :CONVOCATION OR etat.code = :ACCEPTER')
            ->andWhere('entretien.campagne = :campagne')
            ->andWhere('agent in (:agents)')
            ->setParameter('CONVOCATION', EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTATION)
            ->setParameter('ACCEPTER', EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER)
            ->setParameter('campagne', $campagne)
            ->setParameter('agents', $agents)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getAgentsAvecEntretiensFaits(Campagne $campagne, array $agents)
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->join('entretien.agent', 'agent')->addSelect('agent')
            ->join('entretien.etat','etat')->addSelect('etat')
            ->andWhere('etat.code = :RESPONSABLE OR etat.code = :OBSERVATION OR etat.code = :AUTORITE')
            ->andWhere('entretien.campagne = :campagne')
            ->andWhere('agent in (:agents)')
            ->setParameter('RESPONSABLE', EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_RESPONSABLE)
            ->setParameter('OBSERVATION', EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_OBSERVATION)
            ->setParameter('AUTORITE', EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_HIERARCHIE)
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
            ->setParameter('code', EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT)
            ->setParameter('campagne', $campagne)
            ->setParameter('agents', $agents)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Campagne $campagne
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensProfessionnels(Campagne $campagne) : array
    {
        $entretiens = [];
        foreach ($campagne->getEntretiensProfessionnels() as $entretien) {
            if ($entretien->estNonHistorise()) {
                $entretiens[$entretien->getAgent()->getId()][] = $entretien;
            }
        }
        return $entretiens;
    }


    /**
     * @param Campagne $campagne
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensEnAttenteResponsable(Campagne $campagne) : array
    {
        $etats = [
            $this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTATION),
            $this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER),
        ];

        $entretiens = $this->getEntretiensByCampagneAndEtats($campagne, $etats);
        return $entretiens;
    }

    /**
     * @param Campagne $campagne
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensEnAttenteAutorite(Campagne $campagne) : array
    {
        $etats = [
            $this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_RESPONSABLE),
            $this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_OBSERVATION),
        ];

        $entretiens = $this->getEntretiensByCampagneAndEtats($campagne, $etats);
        return $entretiens;
    }

    /**
     * @param Campagne $campagne
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensEnAttenteAgent(Campagne $campagne) : array
    {
        $etats = [
            $this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_HIERARCHIE),
        ];

        $entretiens = $this->getEntretiensByCampagneAndEtats($campagne, $etats);
        return $entretiens;
    }

    /**
     * @param Campagne $campagne
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensCompletes(Campagne $campagne) : array
    {
        $etats = [
            $this->getEtatService()->getEtatByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT),
        ];

        $entretiens = $this->getEntretiensByCampagneAndEtats($campagne, $etats);
        return $entretiens;
    }

    public function getAgentsEligibles(Campagne $campagne) : array
    {
        $agents = $this->getAgentService()->getAgents();
        $agents = array_filter($agents, function (Agent $a) use ($campagne) { return $campagne->isEligible($a); });
        return $agents;
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

    /**
     * Cette fonction retourne la liste des agents adminstratifs associés à des structures aux débuts de la campagne
     * @param array $structures
     * @param Campagne $campagne
     * @return Agent[]
     */
    public function computeAgentByStructures(array $structures, Campagne $campagne) : array
    {
        $qb = $this->getEntityManager()->getRepository(Agent::class)->createQueryBuilder('agent')
            ->join('agent.affectations', 'affectation')
            ->join('agent.statuts', 'statut')
            ->join('agent.grades', 'grade')
            ->andWhere('affectation.dateFin IS NULL OR affectation.dateDebut <= :date')
            ->andWhere('affectation.dateFin IS NULL OR affectation.dateFin >= :date')->setParameter('date', $campagne->getDateDebut())
            // Affecté·e à une des structures
            ->andWhere('affectation.structure in (:structures)')->setParameter('structures', $structures)
            // En contrat ...
            ->andWhere('statut.titulaire = :on OR statut.cdd = :on OR statut.cdi = :on')->setParameter('on', 'O')
            ->andWhere('statut.retraite = :off')->setParameter('off', 'N')
            // Est Administratif
            ->andWhere('statut.administratif = :on')
            ->orderBy('agent.nomUsuel, agent.prenom','ASC')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }


	public function trierAgents(Campagne $campagne, array $agents) : array
    {
        $obligatoires = [];  $facultatifs = [];
        $dateMinEnPoste = (DateTime::createFromFormat('d/m/Y', $campagne->getDateFin()->format('d/m/Y')))->sub(new DateInterval('P12M'));

        /** @var Agent $agent */
        foreach ($agents as $agent) {
            $ok = false;
            if (empty($agent->getAffectationsActifs($dateMinEnPoste))) {
                $facultatifs[] = $agent;
                $ok = true;
            }
            $res = $this->getAgentService()->isValideEmploiType($agent,  $this->getParametreService()->getParametreByCode(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::TEMOIN_EMPLOITYPE), $dateMinEnPoste);
            if (!$ok && !$res) {
                $facultatifs[] = $agent;
                $ok = true;
            }
            if (!$ok) $obligatoires[] = $agent;
        }
        return [$obligatoires, $facultatifs];
    }
}
