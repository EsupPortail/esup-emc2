<?php

namespace Formation\Service\Session;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use DateInterval;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\Formateur;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\Session;
use Formation\Entity\Db\Inscription;
use Formation\Provider\Etat\SessionEtats;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Provider\Template\MailTemplates;
use Formation\Service\Abonnement\AbonnementServiceAwareTrait;
use Formation\Service\Evenement\RappelAgentAvantFormationServiceAwareTrait;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\UserInterface;

class SessionService
{
    use ProvidesObjectManager;
    use EtatTypeServiceAwareTrait;
    use EtatInstanceServiceAwareTrait;
    use AbonnementServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RappelAgentAvantFormationServiceAwareTrait;


    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Session $instance
     * @return Session
     */
    public function create(Session $instance): Session
    {
        $this->getObjectManager()->persist($instance);
        $this->getObjectManager()->flush($instance);
        return $instance;
    }

    /**
     * @param Session $instance
     * @return Session
     */
    public function update(Session $instance): Session
    {
        $this->getObjectManager()->flush($instance);
        return $instance;
    }

    /**
     * @param Session $instance
     * @return Session
     */
    public function historise(Session $instance): Session
    {
        $instance->historiser();
        $this->getObjectManager()->flush($instance);
        return $instance;
    }

    /**
     * @param Session $instance
     * @return Session
     */
    public function restore(Session $instance): Session
    {
        $instance->dehistoriser();
        $this->getObjectManager()->flush($instance);
        return $instance;
    }

    /**
     * @param Session $instance
     * @return Session
     */
    public function delete(Session $instance): Session
    {
        $this->getObjectManager()->remove($instance);
        $this->getObjectManager()->flush($instance);
        return $instance;
    }

    /** Querybuilder et décorateur ************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Session::class)->createQueryBuilder('Finstance')
            ->addSelect('formation')->join('Finstance.formation', 'formation')
            ->addSelect('journee')->leftJoin('Finstance.journees', 'journee')
            ->addSelect('inscription')->leftJoin('Finstance.inscriptions', 'inscription')
////                ->addSelect('agent')->leftJoin('inscrit.agent', 'agent')
////                ->addSelect('affectation')->leftJoin('agent.affectations', 'affectation')
////                ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
            ->addSelect('etat')->leftjoin('Finstance.etats', 'etat')
            ->addSelect('etype')->leftjoin('etat.type', 'etype')
            ->addSelect('formateur')->leftjoin('Finstance.formateurs', 'formateur')
            ->andWhere('etat.histoDestruction IS NULL');
        return $qb;
    }

    static public function decorateWithGestionnairesId(QueryBuilder $qb, array $gestionnaires, bool $addJointure = true): QueryBuilder
    {
        if ($addJointure) {
            $qb = $qb->leftJoin('Finstance.gestionnaires', 'decorateurGestionnaire')->addSelect('decorateurGestionnaire');
        }
        $qb = $qb->andWhere('decorateurGestionnaire.id = (:gestionnaires)')->setParameter('gestionnaires', $gestionnaires);
        return $qb;
    }

    static public function decorateWithGroupeId(QueryBuilder $qb, array $themes, bool $addJointure = true): QueryBuilder
    {
        if ($addJointure) {
            $qb = $qb
                ->leftJoin('Finstance.formation', 'decorateurFormation')->addSelect('decorateurFormation')
                ->leftJoin('decorateurFormation.groupe', 'decorateurGroupe')->addSelect('decorateurGroupe');
        }
        $qb = $qb->andWhere('decorateurGroupe.id = (:themes)')->setParameter('themes', $themes);
        return $qb;
    }


    /** Requetage *****************************************************************************************************/

    /**@return Session[] */
    public function getSessions(string $champ = 'id', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('Finstance.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Session[] */
    public function getSessionsByFormation(Formation $formation): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formation.id = :id')->setParameter('id', $formation->getId());

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Session[] */
    public function getSessionsOuvertesByFormation(Formation $formation): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formation.id = :id')->setParameter('id', $formation->getId())
            ->andWhere('etype.code in (:etats)')->setParameter('etats', [SessionEtats::ETAT_INSCRIPTION_OUVERTE]);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $etatCode
     * @return Session[]
     */
    public function getSessionsByEtat(string $etatCode): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etype.code = :code')
            ->setParameter('code', $etatCode);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int|null $id
     * @return Session|null
     */
    public function getSession(?int $id): ?Session
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('Finstance.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Session::class."] partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Session|null
     */
    public function getRequestedSession(AbstractActionController $controller, string $param = 'session'): ?Session
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getSession($id);

        return $result;
    }

    /** @return Session[][] */
    public function getSessionsByGestionnaires(UserInterface $gestionnaire): array
    {
        $qb = $this->createQueryBuilder();
        $qb = SessionService::decorateWithGestionnairesId($qb, [$gestionnaire->getId()]);
        $qb = $qb->andWhere('Finstance.histoDestruction IS NULL');
        /** @var Session[] $sessions */
        $sessions = $qb->getQuery()->getResult();

        $dictionnaire = [];
        foreach (SessionEtats::ETATS_OUVERTS as $etatCode) $dictionnaire[$etatCode] = [];
        foreach ($sessions as $session) $dictionnaire[$session->getEtatActif()->getType()->getCode()][] = $session;

        return $dictionnaire;
    }

    /** @return Session[] */
    public function getSessionsSansGestionnaires(): array
    {
        $qb = $this->createQueryBuilder();
        $qb = $qb->leftJoin('Finstance.gestionnaires', 'gestionnaire')
            ->andWhere('gestionnaire.id IS NULL')
            ->andWhere('Finstance.histoDestruction IS NULL');
        // retrait des états finaux
        $qb = $qb->andWhere('etype.code not in (:etatsfinaux)')->setParameter('etatsfinaux', SessionEtats::ETATS_FINAUX);
        /** @var Session[] $sessions */
        $sessions = $qb->getQuery()->getResult();

        $sessions = array_filter($sessions, function (Session $session) {
            return $session->getEtatActif()->getType() !== SessionEtats::ETAT_CLOTURE_INSTANCE;
        });
        return $sessions;
    }

    /** @return Session[] */
    public function getSessionsWithParams(array $params): array
    {
        $qb = $this->createQueryBuilder();

        if (isset($params['gestionnaires']) and $params['gestionnaires'] != '') {
            /** TODO :: REMOVE THIS */
            $gestionnaires = [$params['gestionnaires']];
            $qb = SessionService::decorateWithGestionnairesId($qb, $gestionnaires);
        }
        if (isset($params['etats']) and $params['etats'] != '') {
            /** TODO :: REMOVE THIS */
            if (is_string($params['etats'])) $etats = [$params['etats']]; else $etats = $params['etats'];
            $qb = Session::decorateWithEtatsCodes($qb, 'Finstance', $etats);
        }
        if (isset($params['themes']) and $params['themes'] != '') {
            /** TODO :: REMOVE THIS */
            $themes = [$params['themes']];
            $qb = SessionService::decorateWithGroupeId($qb, $themes);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE  *******************************************************************************************************/

    public function createSession(Formation $formation): Session
    {
        $instance = new Session();
        $instance->setType(Session::TYPE_INTERNE);
        $instance->setAutoInscription(true);
        $instance->setNbPlacePrincipale($this->getParametreService()->getValeurForParametre(FormationParametres::TYPE, FormationParametres::NB_PLACE_PRINCIPALE));
        $instance->setNbPlaceComplementaire($this->getParametreService()->getValeurForParametre(FormationParametres::TYPE, FormationParametres::NB_PLACE_COMPLEMENTAIRE));
        $instance->setFormation($formation);
        $this->create($instance);
        $this->getEtatInstanceService()->setEtatActif($instance, SessionEtats::ETAT_CREATION_EN_COURS);
        $instance->setSource(HasSourceInterface::SOURCE_EMC2);
        $instance->setIdSource(($formation->getIdSource()) ? (($formation->getIdSource()) . "-" . $instance->getId()) : ($formation->getId() . "-" . $instance->getId()));
        $this->update($instance);

        return $instance;
    }

    /** Fonctions associées aux états de l'instance *******************************************************************/


    public function recreation(Session $instance): Session
    {
        $this->getEtatInstanceService()->setEtatActif($instance, SessionEtats::ETAT_CREATION_EN_COURS);
        $this->update($instance);

        return $instance;
    }


    /**
     * @param Session $instance
     * @return Session
     */
    public function ouvrirInscription(Session $instance): Session
    {
        $this->getEtatInstanceService()->setEtatActif($instance, SessionEtats::ETAT_INSCRIPTION_OUVERTE);
        $this->update($instance);


        //notification abonnement
        $abonnements = $instance->getFormation()->getAbonnements();
        foreach ($abonnements as $abonnement) {
            if ($abonnement->estNonHistorise()) $this->getNotificationService()->triggerNouvelleSession($instance, $abonnement);
        }

        return $instance;
    }

    /**
     * @param Session $instance
     * @return Session
     */
    public function fermerInscription(Session $instance): Session
    {
        $this->getEtatInstanceService()->setEtatActif($instance, SessionEtats::ETAT_INSCRIPTION_FERMEE);
        $this->update($instance);

        foreach ($instance->getListePrincipale() as $inscrit) {
            $this->getNotificationService()->triggerListePrincipale($inscrit);
            $agent = $inscrit->getAgent();
            $formation = $inscrit->getSession()->getFormation();
            if ($inscrit->isInterne()) {
                $abonnement = $this->getAbonnementService()->getAbonnementByAgentAndFormation($agent, $formation);
                if ($abonnement !== null) $this->getAbonnementService()->retirerAbonnement($agent, $formation);
            }
        }
        foreach ($instance->getListeComplementaire() as $inscrit) {
            $this->getNotificationService()->triggerListeComplementaire($inscrit);
            $agent = $inscrit->getAgent();
            $formation = $inscrit->getSession()->getFormation();
            if ($inscrit->isInterne()) {
                $abonnement = $this->getAbonnementService()->getAbonnementByAgentAndFormation($agent, $formation);
                if ($abonnement === null) $this->getAbonnementService()->ajouterAbonnement($agent, $formation);
            }
        }

        $debut = $instance->getDebut();
        if ($debut !== null) {
            $dateRappel = DateTime::createFromFormat('d/m/Y H:i', $instance->getDebut() . " 08:00");
            $dateRappel->sub(new DateInterval('P4D'));
            $this->getRappelAgentAvantFormationService()->creer($instance, $dateRappel);
        } else {
            throw new RuntimeException("Aucun événement/rappel ne peut être créé sans au moins une séance de planifiée", 0);
        }

        return $instance;
    }

    public function envoyerConvocation(Session $session, ?Inscription $inscription = null): Session
    {
        if ($inscription === null) {
            $this->getEtatInstanceService()->setEtatActif($session, SessionEtats::ETAT_FORMATION_CONVOCATION);
            $this->update($session);
        }

        $liste = [];
        if ($inscription !== null) $liste[] = $inscription; else $liste = $session->getListePrincipale();
        foreach ($liste as $inscrit) {
            if ($inscrit->estNonHistorise()) $this->getNotificationService()->triggerConvocation($inscrit);
        }
        return $session;
    }

    public function envoyerAttestation(Session $session, ?Inscription $inscription = null): Session
    {
        $liste = [];
        if ($inscription !== null) $liste[] = $inscription; else $liste = $session->getListePrincipale();
        foreach ($liste as $inscrit) {
            if ($inscrit->estNonHistorise()) $this->getNotificationService()->triggerAttestation($inscrit);
        }
        return $session;
    }


    public function envoyerAbsence(Session $session, ?Inscription $inscription = null): Session
    {
        $liste = [];
        if ($inscription !== null) $liste[] = $inscription; else $liste = $session->getListePrincipale();
        foreach ($liste as $inscrit) {
            if ($inscrit->estNonHistorise()) $this->getNotificationService()->triggerConstatAbsence($inscrit);
        }
        return $session;
    }

    /**
     * @param Session $instance
     * @return Session
     */
    public function envoyerEmargement(Session $instance): Session
    {
        if ($instance->isEmargementActive()) {
            $this->update($instance);
            $this->getNotificationService()->triggerLienPourEmargement($instance);
        }
        return $instance;
    }

    /**
     * @param Session $instance
     * @return Session
     */
    public function demanderRetour(Session $instance): Session
    {
        $this->getEtatInstanceService()->setEtatActif($instance, SessionEtats::ETAT_ATTENTE_RETOURS);
        $this->update($instance);

        foreach ($instance->getListePrincipale() as $inscrit) {
            $this->getNotificationService()->triggerDemandeRetour($inscrit);
        }
        return $instance;
    }

    /**
     * @param Session $instance
     * @return Session
     */
    public function cloturer(Session $instance): Session
    {
        //changement de l'état de la session
        $this->getEtatInstanceService()->setEtatActif($instance, SessionEtats::ETAT_CLOTURE_INSTANCE);
        $this->update($instance);

        //ajout d'un abonnement à la formation aux inscrits de la liste complémentaire
        $formation = $instance->getFormation();
        foreach ($instance->getListeComplementaire() as $inscription) {
            $agent = $inscription->getAgent();
            if ($agent !== null) { // exclusion des stagiaires externes
                $abonnement = $this->getAbonnementService()->getAbonnementByAgentAndFormation($agent, $formation);
                if ($abonnement === null) {
                    $this->getAbonnementService()->ajouterAbonnement($agent, $formation);
                    $this->getNotificationService()->triggerAjoutAbonnementPostCloture($inscription);
                }
            }
        }
        return $instance;
    }

    /**
     * @param Session $instance
     * @return Session
     */
    public function annuler(Session $instance): Session
    {
        $this->getEtatInstanceService()->setEtatActif($instance, SessionEtats::ETAT_SESSION_ANNULEE);
        $this->update($instance);
        foreach ($instance->getInscriptions() as $inscrit) {
            $this->getNotificationService()->triggerSessionAnnulee($inscrit);
            $agent = $inscrit->getAgent();
            $formation = $inscrit->getSession()->getFormation();
            $abonnement = $this->getAbonnementService()->getAbonnementByAgentAndFormation($agent, $formation);
            if ($abonnement === null) $this->getAbonnementService()->ajouterAbonnement($agent, $formation);
        }
        return $instance;
    }

    /**
     * @param Session $instance
     * @return Session
     */
    public function reouvrir(Session $instance): Session
    {
        $this->getEtatInstanceService()->setEtatActif($instance, SessionEtats::ETAT_CREATION_EN_COURS);
        $this->update($instance);
        return $instance;
    }

    /** Fonction de classement des inscriptions ***********************************************************************/

    public function classerInscription(Inscription $inscription): Inscription
    {
        $session = $inscription->getSession();
        $placePrincipale = $session->getPlaceDisponible(Inscription::PRINCIPALE);
        if ($session->getNbPlacePrincipale() > $placePrincipale) {
            $inscription->setListe(Inscription::PRINCIPALE);
            $this->getObjectManager()->flush($inscription);
            return $inscription;
        }
        $placeComplementaire = $session->getPlaceDisponible(Inscription::COMPLEMENTAIRE);
        if ($session->getNbPlaceComplementaire() > $placeComplementaire) {
            $inscription->setListe(Inscription::COMPLEMENTAIRE);
            $this->getObjectManager()->flush($inscription);
            return $inscription;
        }
        return $inscription;
    }

    /**
     * @return Session[]
     * @attention se base sur l'etat !
     */
    public function getSessionsEnCours(): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etat IS NOT NULL')
            ->andWhere('etype.code <> :annuler AND etype.code <> :cloturer')
            ->setParameter('cloturer', SessionEtats::ETAT_CLOTURE_INSTANCE)
            ->setParameter('annuler', SessionEtats::ETAT_SESSION_ANNULEE);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Session[] */
    public function getNouvellesSessions(): array
    {
        $qb = $this->createQueryBuilder();
        $qb = Session::decorateWithEtatsCodes($qb,'Finstance', [SessionEtats::ETAT_INSCRIPTION_OUVERTE]);

        $sessions = $qb->getQuery()->getResult();
        $sessions = array_filter($sessions, function(Session $s) {return !$s->hasMailWithTemplateCode("Template_".MailTemplates::FORMATION_NOUVELLES_FORMATIONS);});

        return $sessions;
    }

    /**
     * @return Session[]
     */
    public function getSessionsByTerm(mixed $term): array
    {
        $qb = $this->createQueryBuilder();
        $qb = $qb->andWhere("LOWER(formation.libelle) like :search")
            ->setParameter('search', '%' . strtolower($term) . '%');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function formatSessionJSON(array $sessions): array
    {
        $result = [];
        /** @var Session[] $sessions */
        foreach ($sessions as $session) {
            $formation = $session->getFormation();
            $groupe = $formation->getGroupe();
            $result[] = array(
                'id' => $session->getId(),
                'label' => ($groupe ? $groupe->getLibelle() : "Acucun groupe") . " > " . $session->getFormation()->getLibelle(),
                'extra' => "<span class='badge' style='background-color: slategray;'>" . $session->getPeriode() . "</span>",
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }

    /** Gestion des fomateurs *****************************************************************************************/

    public function ajouterFormateur(Session $session, Formateur $formateur): void
    {
        $session->addFormateur($formateur);
        $this->update($session);
    }

    public function retirerFormateur(Session $session, Formateur $formateur): void
    {
        $session->removeFormateur($formateur);
        $this->update($session);
    }
}