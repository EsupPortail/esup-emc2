<?php

namespace EntretienProfessionnel\Service\Campagne;

use Application\Entity\Db\Agent;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateInterval;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use EntretienProfessionnel\Entity\Db\AgentForceSansObligation;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Provider\Parametre\EntretienProfessionnelParametres;
use EntretienProfessionnel\Service\AgentForceSansObligation\AgentForceSansObligationServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class CampagneService
{
    use ProvidesObjectManager;
    use AgentServiceAwareTrait;
    use AgentForceSansObligationServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use ParametreServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Campagne $campagne): Campagne
    {
        $this->getObjectManager()->persist($campagne);
        $this->getObjectManager()->flush($campagne);
        return $campagne;
    }

    public function update(Campagne $campagne): Campagne
    {
        $this->getObjectManager()->flush($campagne);
        return $campagne;
    }

    public function historise(Campagne $campagne): Campagne
    {
        $campagne->historiser();
        $this->getObjectManager()->flush($campagne);
        return $campagne;
    }

    public function restore(Campagne $campagne): Campagne
    {
        $campagne->dehistoriser();
        $this->getObjectManager()->flush($campagne);
        return $campagne;
    }

    public function delete(Campagne $campagne): Campagne
    {
        $this->getObjectManager()->remove($campagne);
        $this->getObjectManager()->flush($campagne);
        return $campagne;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Campagne::class)->createQueryBuilder('campagne')
            ->addSelect('precede')->leftJoin('campagne.precede', 'precede')
            ->addSelect('entretien')->leftJoin('campagne.entretiens', 'entretien');
//            $qb = EntretienProfessionnel::decorateWithEtats($qb, "entretien");
        return $qb;
    }

    /** @return Campagne[] */
    public function getCampagnes(string $champ = 'annee', string $ordre = 'DESC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('campagne.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return array (id => string) */
    public function getCampagnesAsOptions(string $champ = 'annee', string $ordre = 'DESC'): array
    {
        $campagnes = $this->getCampagnes($champ, $ordre);

        $array = [];
        foreach ($campagnes as $campagne) {
            $array[$campagne->getId()] = $campagne->getAnnee();
        }
        return $array;
    }

    public function getCampagne(?int $id): ?Campagne
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('campagne.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EntretienProfessionnelCampagne partage le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedCampagne(AbstractActionController $controller, string $param = "campagne"): ?Campagne
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCampagne($id);
        return $result;
    }

    /** @return Campagne[] */
    public function getCampagnesActives(?DateTime $date = null): array
    {
        $qb = $this->createQueryBuilder();
        $qb = Campagne::decorateWithActif($qb, 'campagne', $date);
        $result = $qb->getQuery()->getResult();
        return $result;
    }


    public function getLastCampagne(?DateTime $date = null): ?Campagne
    {
        if ($date === null) $date = new DateTime();
        $qb = $this->createQueryBuilder()
            ->andWhere('campagne.dateFin < :date')
            ->setParameter('date', $date);
        $result = $qb->getQuery()->getResult();
        $last = null;
        /** @var Campagne $item */
        foreach ($result as $item) {
            if ($last === null or $item->getAnnee() > $last->getAnnee()) $last = $item;
        }
        return $last;
    }

    /**
     * @return EntretienProfessionnel[]
     * todo a mettre dans EPS
     */
    public function getEntretiensByCampagneAndEtats(Campagne $campagne, array $etats): array
    {
        $qb = $this->getObjectManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->andWhere('entretien.campagne in (:campagne)')->setParameter('campagne', $campagne)
            ->andWhere('entretien.histoDestruction IS NULL');
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
    public function getEntretiensProfessionnels(Campagne $campagne): array
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
    public function getEntretiensEnAttenteResponsable(Campagne $campagne): array
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
    public function getEntretiensEnAttenteAutorite(Campagne $campagne): array
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
    public function getEntretiensEnAttenteAgent(Campagne $campagne): array
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
    public function getEntretiensCompletes(Campagne $campagne): array
    {
        $etats = [
            $this->getEtatTypeService()->getEtatTypeByCode(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT),
        ];

        $entretiens = $this->getEntretiensByCampagneAndEtats($campagne, $etats);
        return $entretiens;
    }

    public function getAgentsEligibles(Campagne $campagne): array
    {
        $agents = $this->getAgentService()->getAgentsWithDates($campagne->getDateDebut(), $campagne->getDateFin());
        $agents = $this->getAgentService()->filtrerWithAffectationTemoin($agents, $this->getParametreService()->getParametreByCode(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::TEMOIN_AFFECTATION));
        $agents = $this->getAgentService()->filtrerWithStatutTemoin($agents, $this->getParametreService()->getParametreByCode(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::TEMOIN_STATUT));
        $agents = $this->getAgentService()->filtrerWithEmploiTypeTemoin($agents, $this->getParametreService()->getParametreByCode(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::TEMOIN_EMPLOITYPE));
        $agents = $this->getAgentService()->filtrerWithGradeTemoin($agents, $this->getParametreService()->getParametreByCode(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::TEMOIN_GRADE));
        $agents = $this->getAgentService()->filtrerWithCorpsTemoin($agents, $this->getParametreService()->getParametreByCode(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::TEMOIN_CORPS));

        $sansObligations = array_map(function (AgentForceSansObligation $a) { return $a->getAgent(); }, $this->getAgentForceSansObligationService()->getAgentsForcesSansObligationByCampagne($campagne));
        $agentsFinales = [];
        foreach ($agents as $agent) {
            $keep = true;
            foreach ($sansObligations as $sansObligation) {
                if ($agent === $sansObligation) {
                    $keep = false; break;
                }
            }
            if ($keep) $agentsFinales[] = $agent;
        }

        return $agentsFinales;
    }

    /** FACADE ********************************************************************************************************/

    public static function getAnneeScolaire(): string
    {
        $date = new DateTime();
        $annee = ((int)$date->format("Y"));
        $mois = ((int)$date->format("m"));

        if ($mois < 9) {
            $scolaire = ($annee - 1) . "/" . ($annee);
        } else {
            $scolaire = ($annee) . "/" . ($annee + 1);
        }
        return $scolaire;
    }

    public function trierAgents(Campagne $campagne, array $agents): array
    {
        $obligatoires = [];
        $facultatifs = [];
        $raison = [];
        $dateMinEnPoste = (DateTime::createFromFormat('d/m/Y', $campagne->getDateFin()->format('d/m/Y')))->sub(new DateInterval('P12M'));

        /** @var Agent $agent */
        foreach ($agents as $agent) {
            $raison[$agent->getId()] = "<ul>";

            $kept = true;

            if ($agent->isForceSansObligation($campagne)) {
                $raison[$agent->getId()] .= "<li>Forcé·e sans obligation</li>";
                $kept = false;
            }


            if (!$agent->isContratLong()) {
                $kept = false;
                $raison[$agent->getId()] .= "<li>Sans 'contrat long'</li>";
            }
            if (!$agent->isValideEmploiType($this->getParametreService()->getParametreByCode(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::TEMOIN_EMPLOITYPE), $dateMinEnPoste)) {
                $kept = false;
                $raison[$agent->getId()] .= "<li>Emploi-type invalide dans le cadre des entretiens professionnels</li>";
            }
            if (!$agent->isValideStatut($this->getParametreService()->getParametreByCode(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::TEMOIN_STATUT), $dateMinEnPoste))
            {
                $kept = false;
                $raison[$agent->getId()] .= "<li>Statut invalide dans le cadre des entretiens professionnels</li>";

            }
            if (!$agent->isValideAffectation($this->getParametreService()->getParametreByCode(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::TEMOIN_AFFECTATION), $dateMinEnPoste))
            {
                $kept = false;
                $raison[$agent->getId()] .= "<li>Sans affectation valide (à la date du ".$dateMinEnPoste->format('d/m/y').") </li>";

            }
            if ($kept) $obligatoires[] = $agent; else $facultatifs[] = $agent;
            $raison[$agent->getId()] .= "</ul>";
        }
        return [$obligatoires, $facultatifs, $raison];
    }
}