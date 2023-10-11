<?php

namespace EntretienProfessionnel\Service\Campagne;

use Application\Entity\Db\Agent;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateInterval;
use DateTime;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Provider\Parametre\EntretienProfessionnelParametres;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class CampagneService {
    use EntityManagerAwareTrait;
    use AgentServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use ParametreServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

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

    public function update(Campagne $campagne) : Campagne
    {
        try {
            $this->getEntityManager()->flush($campagne);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $campagne;
    }

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

    public function createQueryBuilder() : QueryBuilder
    {
        try {
            $qb = $this->getEntityManager()->getRepository(Campagne::class)->createQueryBuilder('campagne')
                ->addSelect('precede')->leftJoin('campagne.precede', 'precede')
                ->addSelect('entretien')->leftJoin('campagne.entretiens', 'entretien')
            ;
//            $qb = EntretienProfessionnel::decorateWithEtats($qb, "entretien");
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problème est survenu lors de la création du QueryBuilder de [".Campagne::class."]",0,$e);
        }

        return $qb;
    }

    /** @return Campagne[] */
    public function getCampagnes(string $champ='annee', string $ordre='DESC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('campagne.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return array (id => string) */
    public function getCampagnesAsOptions(string $champ='annee', string $ordre='DESC') : array
    {
        $campagnes = $this->getCampagnes($champ, $ordre);

        $array = [];
        foreach ($campagnes as $campagne) {
            $array[$campagne->getId()] = $campagne->getAnnee();
        }
        return $array;
    }

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

    public function getRequestedCampagne(AbstractActionController $controller, string $param = "campagne") : ?Campagne
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCampagne($id);
        return $result;
    }

    /** @return Campagne[] */
    public function getCampagnesActives(?DateTime $date = null) : array
    {
        $qb = $this->createQueryBuilder();
        $qb = Campagne::decorateWithActif($qb, 'campagne', $date);
        $result = $qb->getQuery()->getResult();
        return $result;
    }


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
     * @return EntretienProfessionnel[]
     * todo a mettre dans EPS
     */
    public function getEntretiensByCampagneAndEtats(Campagne $campagne, array $etats) : array
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->andWhere('entretien.campagne in (:campagne)')->setParameter('campagne', $campagne)
            ->andWhere('entretien.histoDestruction IS NULL')
        ;
        $qb = EntretienProfessionnel::decorateWithEtats($qb, 'entretien', $etats);
        $result = $qb->getQuery()->getResult();

        $entretiens = [];
        /** @var EntretienProfessionnel $entretien */
        foreach ($result as $entretien) {
            $entretiens[$entretien->getAgent()->getId()][] = $entretien;
        }
        return $entretiens;
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
            $this->getEtatTypeService()->getEtatTypeByCode(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTATION),
            $this->getEtatTypeService()->getEtatTypeByCode(EntretienProfessionnelEtats::ETAT_ENTRETIEN_ACCEPTER),
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
            $this->getEtatTypeService()->getEtatTypeByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_RESPONSABLE),
            $this->getEtatTypeService()->getEtatTypeByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_OBSERVATION),
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
            $this->getEtatTypeService()->getEtatTypeByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_HIERARCHIE),
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
            $this->getEtatTypeService()->getEtatTypeByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT),
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