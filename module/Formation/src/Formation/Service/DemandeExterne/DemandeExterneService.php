<?php

namespace Formation\Service\DemandeExterne;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Interfaces\HasSourceInterface;
use DateTime;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\DemandeExterne;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationGroupe;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Entity\Db\Presence;
use Formation\Entity\Db\Seance;
use Formation\Provider\Etat\DemandeExterneEtats;
use Formation\Provider\Etat\InscriptionEtats;
use Formation\Provider\Etat\SessionEtats;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use Formation\Service\Presence\PresenceAwareTrait;
use Formation\Service\Seance\SeanceServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Structure\Entity\Db\Structure;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenEtat\Entity\Db\EtatType;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;

class DemandeExterneService
{
    use EntityManagerAwareTrait;
    use EtatInstanceServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use FormationServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use SeanceServiceAwareTrait;
    use PresenceAwareTrait;

    use StructureServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;

    /** GESTION ENTITE ************************************************************************************************/

    public function create(DemandeExterne $demande): DemandeExterne
    {
        try {
            $this->getEntityManager()->persist($demande);
            $this->getEntityManager()->flush($demande);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue en BD.", 0, $e);
        }
        return $demande;
    }

    public function update(DemandeExterne $demande): DemandeExterne
    {
        try {
            $this->getEntityManager()->flush($demande);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue en BD.", 0, $e);
        }
        return $demande;
    }

    public function historise(DemandeExterne $demande): DemandeExterne
    {
        try {
            $demande->historiser();
            $this->getEntityManager()->flush($demande);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue en BD.", 0, $e);
        }
        return $demande;
    }

    public function restore(DemandeExterne $demande): DemandeExterne
    {
        try {
            $demande->dehistoriser();
            $this->getEntityManager()->flush($demande);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue en BD.", 0, $e);
        }
        return $demande;
    }

    public function delete(DemandeExterne $demande): DemandeExterne
    {
        try {
            $this->getEntityManager()->remove($demande);
            $this->getEntityManager()->flush($demande);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue en BD.", 0, $e);
        }
        return $demande;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        try {
            $qb = $this->getEntityManager()->getRepository(DemandeExterne::class)->createQueryBuilder('demande')
                ->join('demande.agent', 'agent')->addSelect('agent')
                ->join('demande.etats', 'etat')->addSelect('etat')
                ->join('etat.type', 'type')->addSelect('type')
                ->andWhere('etat.histoDestruction IS NULL')
                ;
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problème est survenu lors de la création du QueryBuilder de [" . DemandeExterne::class . "]", 0, $e);
        }
        return $qb;
    }

    public function getDemandeExterne(?int $id): ?DemandeExterne
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('demande.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs DemandeExterne partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedDemandeExterne(AbstractActionController $controller, string $param = 'demande-externe'): ?DemandeExterne
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getDemandeExterne($id);
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return DemandeExterne[]
     */
    public function getDemandesExternes(string $champ = 'histoCreation', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('demande.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param array $params ['agent' Agent, 'organisme' string, 'etat' Etat, 'histo' ??? ]
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getDemandesExternesWithFiltre(array $params, string $champ = 'histoCreation', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()->orderBy('demande.' . $champ, $ordre);

        if (isset($params['agent'])) $qb = $qb->andWhere('demande.agent = :agent')->setParameter('agent', $params['agent']);
        if (isset($params['organisme']) && trim($params['organisme'] !== '')) $qb = $qb->andWhere('demande.organisme = :organisme')->setParameter('organisme', $params['organisme']);
        if (isset($params['etat'])) $qb = $qb->andWhere('etat.type = :etat')->setParameter('etat', $params['etat'])->andWhere('etat.histoDestruction IS NULL');
        if (isset($params['historise'])) {
            if ($params['historise'] === '1') $qb = $qb->andWhere('demande.histoDestruction IS NOT NULL');
            if ($params['historise'] === '0') $qb = $qb->andWhere('demande.histoDestruction IS NULL');
        }
        if (isset($params['annee']) and $params['annee'] !== '') {
            $annee = (int)$params['annee'];
            $debut = DateTime::createFromFormat('d/m/Y', '01/09/' . $annee);
            $fin = DateTime::createFromFormat('d/m/Y', '31/08/' . ($annee + 1));
            $qb = $qb
                ->andWhere('demande.fin >= :debut')->setParameter('debut', $debut)
                ->andWhere('demande.debut <= :fin')->setParameter('fin', $fin);
        }

        $result = $qb->getQuery()->getResult();
        return $result;

    }

    /**
     * @param Agent $agent
     * @param string $champ
     * @param string $ordre
     * @return DemandeExterne[]
     */
    public function getDemandesExternesByAgent(Agent $agent, string $champ = 'histoCreation', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('demande.agent = :agent')->setParameter('agent', $agent)
            ->orderBy('demande.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    /**
     * @param Structure $structure
     * @param bool $avecStructuresFilles
     * @param bool $anneeCourrante
     * @return DemandeExterne[]
     */
    public function getDemandeByStructure(Structure $structure, bool $avecStructuresFilles = false, bool $anneeCourrante = false): array
    {
        $structures = [];
        $structures[] = $structure;

        if ($avecStructuresFilles === true) {
            $structures = $this->getStructureService()->getStructuresFilles($structure);
            $structures[] = $structure;
        }

        $qb = $this->createQueryBuilder()
            ->addSelect('affectation')->join('agent.affectations', 'affectation')
            ->andWhere('affectation.structure in (:structures)')
            ->setParameter('structures', $structures)
            ->andWhere('demande.histoDestruction IS NULL')
//            ->andWhere('inscritetat.code = :demandevalidation')
//            ->setParameter('demandevalidation', FormationInstanceInscrit::ETAT_DEMANDE_INSCRIPTION)
        ;

        if ($anneeCourrante) {
            $annee = Formation::getAnnee();
            $mini = DateTime::createFromFormat('d/m/Y', '01/09/' . $annee);
            $maxi = DateTime::createFromFormat('d/m/Y', '31/08/' . ($annee + 1));

            $qb = $qb->andWhere('demande.histoCreation >= :mini AND demande.histoCreation <= :maxi')
                ->setParameter('mini', $mini)
                ->setParameter('maxi', $maxi);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getDemandesExternesByAgentsAndAnnee(array $agents, ?int $annee = null): array
    {
        if ($annee === null) $annee = Formation::getAnnee();
        $debut = DateTime::createFromFormat('d/m/Y H:i', '01/09/' . $annee . ' 08:00');
        $fin = DateTime::createFromFormat('d/m/Y H:i', '31/08/' . ($annee + 1) . ' 18:00');

        $qb = $this->createQueryBuilder()->orderBy('demande.histoCreation', 'asc')
            ->andWhere('demande.agent in (:agents)')->setParameter('agents', $agents)
            ->andWhere('demande.histoCreation >= :debut')->setParameter('debut', $debut)
            ->andWhere('demande.histoCreation <= :fin')->setParameter('fin', $fin);
        /** @var FormationInstanceInscrit[] $result */
        $result = $qb->getQuery()->getResult();

        $inscriptions = [];
        foreach ($result as $item) {
            $inscriptions[$item->getAgent()->getId()][] = $item;
        }

        return $inscriptions;
    }

    /** FONCTION POUR LA RECHERCHE ************************************************************************************/

    /**
     * @param string $texte
     * @return Agent[]
     */
    public function findAgentByTerm(string $texte): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("LOWER(CONCAT(agent.prenom, ' ', agent.nomUsuel)) like :search OR LOWER(CONCAT(agent.nomUsuel, ' ', agent.prenom)) like :search")
            ->setParameter('search', '%' . strtolower($texte) . '%');
        $result = $qb->getQuery()->getResult();

        $agents = [];
        /** @var DemandeExterne $item */
        foreach ($result as $item) {
            $agent = $item->getAgent();
            $agents[$agent->getId()] = $agent;
        }
        return $agents;
    }

    /**
     * @param string $texte
     * @return Agent[]
     */
    public function findOrganismeByTerm(string $texte): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("LOWER(demande.organisme) like :search")
            ->setParameter('search', '%' . strtolower($texte) . '%');
        $result = $qb->getQuery()->getResult();

        $organismes = [];
        /** @var DemandeExterne $item */
        foreach ($result as $item) {
            $organismes[$item->getOrganisme()] = $item->getOrganisme();
        }
        return $organismes;
    }

    public function transformer(?DemandeExterne $demande, string $libelle, float $volume, float $suivi): FormationInstance
    {
        //theme
        $theme = $this->getFormationGroupeService()->getFormationGroupeByLibelle("Stage externe");
        if ($theme === null) {
            $theme = new FormationGroupe();
            $theme->setLibelle('Stage externe');
            $theme->setOrdre(99999999);
            $theme->setSource(HasSourceInterface::SOURCE_EMC2);
            $this->getFormationGroupeService()->create($theme);
            $theme->setIdSource($theme->getId());
            $this->getFormationGroupeService()->update($theme);
        }

        //creation de l'action de formation
        $formation = new Formation();
        $formation->setLibelle($libelle);
        $formation->setGroupe($theme);
        $formation->setDescription("Action de formation générée depuis la demande " . $demande->getId());
        $formation->setAffichage(false);
        $theme->setSource(HasSourceInterface::SOURCE_EMC2);
        $this->getFormationService()->create($formation);
        $theme->setIdSource($formation->getId());
        $this->getFormationService()->update($formation);

        //session
        $session = new FormationInstance();
        $session->setFormation($formation);
        $session->setAutoInscription();
        $session->setNbPlacePrincipale(1);
        $session->setNbPlaceComplementaire(0);
        $session->setType("stage externe");
        $session->setSource(HasSourceInterface::SOURCE_EMC2);
        $this->getFormationInstanceService()->create($session);
        $this->getEtatInstanceService()->setEtatActif($session, SessionEtats::ETAT_CLOTURE_INSTANCE);
        $session->setIdSource($formation->getId() . "-" . $session->getId());
        $this->getFormationInstanceService()->update($session);

        //inscription
        $inscription = new FormationInstanceInscrit();
        $inscription->setAgent($demande->getAgent());
        $inscription->setInstance($session);
        $inscription->setListe(FormationInstanceInscrit::PRINCIPALE);
        $inscription->setSource(HasSourceInterface::SOURCE_EMC2);
        $this->getFormationInstanceInscritService()->create($inscription);
        $this->getEtatInstanceService()->setEtatActif($inscription, InscriptionEtats::ETAT_VALIDER_DRH);
        $this->getFormationInstanceInscritService()->update($inscription);

        $absence = $volume - $suivi;

        if ($suivi != 0) {
            //seance
            $seance = new Seance();
            $seance->setInstance($session);
            $seance->setVolume($suivi);
            $seance->setLieu("");
            $seance->setType(Seance::TYPE_VOLUME);
            $seance->setVolumeDebut($demande->getDebut());
            $seance->setVolumeFin($demande->getFin());
            $inscription->setSource(HasSourceInterface::SOURCE_EMC2);
            $this->getSeanceService()->create($seance);
            $seance->setIdSource($formation->getId() . "-" . $session->getId() . "-" . $seance->getId());
            $this->getSeanceService()->update($seance);

            //presence true
            $presence = new Presence();
            $presence->setJournee($seance);
            $presence->setInscrit($inscription);
            $presence->setStatut(Presence::PRESENCE_PRESENCE);
            $presence->setPresenceType("stage externe");
            //source ... todo
            $this->getPresenceService()->create($presence);
        }

        if ($absence != 0) {
            //seance
            $seance = new Seance();
            $seance->setInstance($session);
            $seance->setVolume($absence);
            $seance->setLieu("");
            $seance->setType(Seance::TYPE_VOLUME);
            //source ... todo
            $this->getSeanceService()->create($seance);

            //presence false
            $presence = new Presence();
            $presence->setJournee($seance);
            $presence->setInscrit($inscription);
            $presence->setStatut(Presence::PRESENCE_ABSENCE_JUSTIFIEE);
            $presence->setPresenceType("stage externe");
            //source ... todo
            $this->getPresenceService()->create($presence);
        }

        return $session;
    }

    /**
     * @param Agent[] $agents
     * @param EtatType[] $etats
     * @param int|null $annee
     * @return DemandeExterne[]
     */
    public function getDemandesExternesValideesByAgentsAndEtats(array $agents, array $etats, ?int $annee): array
    {
        if ($annee === null) Formation::getAnnee();
        $debut = DateTime::createFromFormat('d/m/Y', '01/09/' . $annee);
        $fin = DateTime::createFromFormat('d/m/Y', '31/08/' . ($annee + 1));

        $qb = $this->createQueryBuilder()
            ->andWhere('demande.histoDestruction IS NULL')
            ->andWhere('type.code in (:etats)')->setParameter('etats', $etats)
            ->andWhere('demande.debut > :debut')->setParameter('debut', $debut)
            ->andWhere('demande.debut < :fin')->setParameter('fin', $fin)
            ->andWhere('agent in (:agents)')->setParameter('agents', $agents);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Agent[] $agents
     * @param int|null $annee
     * @return DemandeExterne[]
     */
    public function getDemandesExternesValideesByAgents(array $agents, ?int $annee): array
    {
        $etats = [DemandeExterneEtats::ETAT_VALIDATION_DRH, DemandeExterneEtats::ETAT_REJETEE, DemandeExterneEtats::ETAT_TERMINEE];
        $result = $this->getDemandesExternesValideesByAgentsAndEtats($agents, $etats, $annee);
        return $result;
    }

    /**
     * @param Agent[] $agents
     * @param int|null $annee
     * @return DemandeExterne[]
     */
    public function getDemandesExternesNonValideesByAgents(array $agents, ?int $annee): array
    {
        $etats = [DemandeExterneEtats::ETAT_VALIDATION_AGENT, DemandeExterneEtats::ETAT_VALIDATION_RESP];
        $result = $this->getDemandesExternesValideesByAgentsAndEtats($agents, $etats, $annee);
        return $result;
    }
}