<?php

namespace Formation\Service\FormationInstance;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use DateInterval;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\Formateur;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\Inscription;
use Formation\Entity\Db\PlanDeFormation;
use Formation\Provider\Etat\SessionEtats;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Service\Abonnement\AbonnementServiceAwareTrait;
use Formation\Service\Evenement\RappelAgentAvantFormationServiceAwareTrait;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Entity\Db\HasEtatsInterface;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class FormationInstanceService
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
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function create(FormationInstance $instance): FormationInstance
    {
        $this->getObjectManager()->persist($instance);
        $this->getObjectManager()->flush($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function update(FormationInstance $instance): FormationInstance
    {
        $this->getObjectManager()->flush($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function historise(FormationInstance $instance): FormationInstance
    {
        $instance->historiser();
        $this->getObjectManager()->flush($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function restore(FormationInstance $instance): FormationInstance
    {
        $instance->dehistoriser();
        $this->getObjectManager()->flush($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function delete(FormationInstance $instance): FormationInstance
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
        $qb = $this->getObjectManager()->getRepository(FormationInstance::class)->createQueryBuilder('Finstance')
            ->addSelect('formation')->join('Finstance.formation', 'formation')
            ->addSelect('journee')->leftJoin('Finstance.journees', 'journee')
            ->addSelect('inscription')->leftJoin('Finstance.inscriptions', 'inscription')
////                ->addSelect('agent')->leftJoin('inscrit.agent', 'agent')
////                ->addSelect('affectation')->leftJoin('agent.affectations', 'affectation')
////                ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
            ->addSelect('etat')->leftjoin('Finstance.etats', 'etat')
            ->addSelect('etype')->leftjoin('etat.type', 'etype')
            ->addSelect('formateur')->leftjoin('Finstance.formateurs', 'formateur')
            ->andWhere('etat.histoDestruction IS NULL')
        ;
        return $qb;
    }

    static public function decorateWithGestionnairesId(QueryBuilder $qb, array $gestionnaires, bool $addJointure = true): QueryBuilder
    {
        if ($addJointure) {
            $qb = $qb->leftJoin('Finstance.gestionnaires', 'gestionnaire')->addSelect('gestionnaire');
        }
        $qb = $qb->andWhere('gestionnaire.id = (:gestionnaires)')->setParameter('gestionnaires', $gestionnaires);
        return $qb;
    }
    static public function decorateWithGroupeId(QueryBuilder $qb, array $themes, bool $addJointure = true): QueryBuilder
    {
        if ($addJointure) {
            $qb = $qb
                ->leftJoin('Finstance.formation', 'decorateurFormation')->addSelect('decorateurFormation')
                ->leftJoin('decorateurFormation.groupe', 'decorateurGroupe')->addSelect('decorateurGroupe')
            ;
        }
        $qb = $qb->andWhere('decorateurGroupe.id = (:themes)')->setParameter('themes', $themes);
        return $qb;
    }


    /** Requetage *****************************************************************************************************/

    /**@return FormationInstance[] */
    public function getFormationsInstances(string $champ = 'id', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('Finstance.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return FormationInstance[] */
    public function getFormationsInstancesByFormation(Formation $formation): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formation.id = :id')->setParameter('id', $formation->getId());

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFormationsInstancesOuvertesByFormation(Formation $formation)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formation.id = :id')->setParameter('id', $formation->getId())
            ->andWhere('etype.code in (:etats)')->setParameter('etats', [SessionEtats::ETAT_INSCRIPTION_OUVERTE]);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFormationsInstancesByFormationAndPlan(Formation $formation, PlanDeFormation $plan): array
    {
        $annees = explode("/", $plan->getLibelle());
        $debut = DateTime::createFromFormat('d/m/Y', '01/09/' . $annees[0]);
        $fin = DateTime::createFromFormat('d/m/Y', '31/08/' . $annees[1]);

        $qb = $this->createQueryBuilder()
            ->andWhere('formation.id = :id')->setParameter('id', $formation->getId());

        $result = $qb->getQuery()->getResult();
        $trueR = [];

        /** @var FormationInstance $instance */
        foreach ($result as $instance) {
            if ($instance->getDebut(true) > $debut and $instance->getFin(true) < $fin) $trueR[] = $instance;

        }
        return $trueR;
    }

    /**
     * @param string $etatCode
     * @return FormationInstance[]
     */
    public function getFormationsInstancesByEtat(string $etatCode): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etype.code = :code')
            ->setParameter('code', $etatCode);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int|null $id
     * @return FormationInstance|null
     */
    public function getFormationInstance(?int $id): ?FormationInstance
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('Finstance.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationInstance partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return FormationInstance|null
     */
    public function getRequestedFormationInstance(AbstractActionController $controller, string $param = 'formation-instance'): ?FormationInstance
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getFormationInstance($id);

        return $result;
    }

    /** @return FormationInstance[] */
    public function getSessionsWithParams(array $params) : array
    {
        $qb = $this->createQueryBuilder();

        if (isset($params['gestionnaires']) AND $params['gestionnaires'] != '') {
            /** TODO :: REMOVE THIS */ $gestionnaires = [ $params['gestionnaires'] ];
            $qb = FormationInstanceService::decorateWithGestionnairesId($qb, $gestionnaires);
        }
        if (isset($params['etats']) AND $params['etats'] != '') {
            /** TODO :: REMOVE THIS */ if (is_string($params['etats'])) $etats = [ $params['etats'] ]; else $etats = $params['etats'];
            $qb = FormationInstance::decorateWithEtatsCodes($qb, 'Finstance', $etats);
        }
        if (isset($params['themes'])  AND $params['themes'] != '') {
            /** TODO :: REMOVE THIS */ $themes = [ $params['themes'] ];
            $qb = FormationInstanceService::decorateWithGroupeId($qb,  $themes);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE  *******************************************************************************************************/

    public function createNouvelleInstance(Formation $formation): FormationInstance
    {
        $instance = new FormationInstance();
        $instance->setType(FormationInstance::TYPE_INTERNE);
        $instance->setAutoInscription(true);
        $instance->setNbPlacePrincipale($this->getParametreService()->getParametreByCode(FormationParametres::TYPE, FormationParametres::NB_PLACE_PRINCIPALE)->getValeur());
        $instance->setNbPlaceComplementaire($this->getParametreService()->getParametreByCode(FormationParametres::TYPE, FormationParametres::NB_PLACE_COMPLEMENTAIRE)->getValeur());
        $instance->setFormation($formation);
        $this->create($instance);
        $this->getEtatInstanceService()->setEtatActif($instance, SessionEtats::ETAT_CREATION_EN_COURS);
        $instance->setSource(HasSourceInterface::SOURCE_EMC2);
        $instance->setIdSource(($formation->getIdSource()) ? (($formation->getIdSource()) . "-" . $instance->getId()) : ($formation->getId() . "-" . $instance->getId()));
        $this->update($instance);

        return $instance;
    }

    /** Fonctions associées aux états de l'instance *******************************************************************/


    public function recreation(FormationInstance $instance): FormationInstance
    {
        $this->getEtatInstanceService()->setEtatActif($instance, SessionEtats::ETAT_CREATION_EN_COURS);
        $this->update($instance);

        return $instance;
    }


    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function ouvrirInscription(FormationInstance $instance): FormationInstance
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
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function fermerInscription(FormationInstance $instance): FormationInstance
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

    public function envoyerConvocation(FormationInstance $session, ?Inscription $inscription=null): FormationInstance
    {
        $this->getEtatInstanceService()->setEtatActif($session, SessionEtats::ETAT_FORMATION_CONVOCATION);
        $this->update($session);

        $liste = [];
        if ($inscription !== null) $liste[] = $inscription; else $liste = $session->getListePrincipale();
        foreach ($liste as $inscrit) {
            if ($inscrit->estNonHistorise()) $this->getNotificationService()->triggerConvocation($inscrit);
        }
        return $session;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function envoyerEmargement(FormationInstance $instance): FormationInstance
    {
        if ($instance->isEmargementActive()) {
            $this->update($instance);
            $this->getNotificationService()->triggerLienPourEmargement($instance);
        }
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function demanderRetour(FormationInstance $instance): FormationInstance
    {
        $this->getEtatInstanceService()->setEtatActif($instance, SessionEtats::ETAT_ATTENTE_RETOURS);
        $this->update($instance);

        foreach ($instance->getListePrincipale() as $inscrit) {
            $this->getNotificationService()->triggerDemandeRetour($inscrit);
        }
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function cloturer(FormationInstance $instance): FormationInstance
    {
        $this->getEtatInstanceService()->setEtatActif($instance, SessionEtats::ETAT_CLOTURE_INSTANCE);
        $this->update($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function annuler(FormationInstance $instance): FormationInstance
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
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function reouvrir(FormationInstance $instance): FormationInstance
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
     * @return FormationInstance[]
     * @attention se base sur l'etat !
     */
    public function getFormationInstanceEnCours(): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etat IS NOT NULL')
            ->andWhere('etype.code <> :annuler AND etype.code <> :cloturer')
            ->setParameter('cloturer', SessionEtats::ETAT_CLOTURE_INSTANCE)
            ->setParameter('annuler', SessionEtats::ETAT_SESSION_ANNULEE);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** !! TODO !! NAZE FAIRE MIEUX */
    public function getNouvelleInstance()
    {
        $date = (new DateTime())->sub(new DateInterval('P1W'));

        $qb = $this->createQueryBuilder()
            ->andWhere('Finstance.histoCreation > :date')->setParameter('date', $date)
            ->andWhere('formation.affichage = :true')->setParameter('true', true);
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @return FormationInstance[]
     */
    public function getSessionByTerm(mixed $term): array
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
        /** @var FormationInstance[] $sessions */
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

    public function ajouterFormateur(FormationInstance $session, Formateur $formateur): void
    {
        $session->addFormateur($formateur);
        $this->update($session);
    }

    public function retirerFormateur(FormationInstance $session, Formateur $formateur): void
    {
        $session->removeFormateur($formateur);
        $this->update($session);
    }
}