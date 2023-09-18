<?php

namespace Formation\Service\FormationInstance;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use DateInterval;
use DateTime;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Formation\Controller\FormationInstanceController;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Entity\Db\PlanDeFormation;
use Formation\Provider\Etat\SessionEtats;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Service\Abonnement\AbonnementServiceAwareTrait;
use Formation\Service\Evenement\RappelAgentAvantFormationServiceAwareTrait;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class FormationInstanceService
{
    use EntityManagerAwareTrait;
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
        try {
            $this->getEntityManager()->persist($instance);
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function update(FormationInstance $instance): FormationInstance
    {
        try {
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function historise(FormationInstance $instance): FormationInstance
    {
        try {
            $instance->historiser();
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function restore(FormationInstance $instance): FormationInstance
    {
        try {
            $instance->dehistoriser();
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function delete(FormationInstance $instance): FormationInstance
    {
        try {
            $this->getEntityManager()->remove($instance);
            $this->getEntityManager()->flush($instance);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $instance;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder
    {
        try {
            $qb = $this->getEntityManager()->getRepository(FormationInstance::class)->createQueryBuilder('Finstance')
                ->addSelect('formation')->join('Finstance.formation', 'formation')
                ->addSelect('journee')->leftJoin('Finstance.journees', 'journee')
                ->addSelect('inscrit')->leftJoin('Finstance.inscrits', 'inscrit')
                ->addSelect('frais')->leftJoin('inscrit.frais', 'frais')
                ->addSelect('agent')->leftJoin('inscrit.agent', 'agent')
                ->addSelect('affectation')->leftJoin('agent.affectations', 'affectation')
                ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
                ->addSelect('etat')->leftjoin('Finstance.etats', 'etat')
                ->addSelect('etype')->leftjoin('etat.type', 'etype');
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problem est survenu lors de la création du QueryBuilder de [".FormationInstanceController::class."]",0,$e);
        }
        return $qb;
    }

    /**@return FormationInstance[] */
    public function getFormationsInstances(string $champ = 'id', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->join('Finstance.source', 'source')->addSelect('source')
            ->orderBy('Finstance.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return FormationInstance[] */
    public function getFormationsInstancesByFormation(Formation $formation): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formation.id = :id')->setParameter('id', $formation->getId())
        ;

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
        $annees = explode("/", $plan->getAnnee());
        $debut = DateTime::createFromFormat('d/m/Y', '01/09/' . $annees[0]);
        $fin = DateTime::createFromFormat('d/m/Y', '31/08/' . $annees[1]);

        $qb = $this->createQueryBuilder()
            ->andWhere('formation.id = :id')->setParameter('id', $formation->getId())
        ;

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
        $this->getEtatInstanceService()->setEtatActif($instance,SessionEtats::ETAT_CREATION_EN_COURS);
        $this->update($instance);

        return $instance;
    }


    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function ouvrirInscription(FormationInstance $instance): FormationInstance
    {
        $this->getEtatInstanceService()->setEtatActif($instance,SessionEtats::ETAT_INSCRIPTION_OUVERTE);
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
        $this->getEtatInstanceService()->setEtatActif($instance,SessionEtats::ETAT_INSCRIPTION_FERMEE);
        $this->update($instance);

        foreach ($instance->getListePrincipale() as $inscrit) {
            $this->getNotificationService()->triggerListePrincipale($inscrit);
            $agent = $inscrit->getAgent();
            $formation = $inscrit->getInstance()->getFormation();
            $abonnement = $this->getAbonnementService()->getAbonnementByAgentAndFormation($agent, $formation);
            if ($abonnement !== null) $this->getAbonnementService()->retirerAbonnement($agent, $formation);
        }
        foreach ($instance->getListeComplementaire() as $inscrit) {
            $this->getNotificationService()->triggerListeComplementaire($inscrit);
            $agent = $inscrit->getAgent();
            $formation = $inscrit->getInstance()->getFormation();
            $abonnement = $this->getAbonnementService()->getAbonnementByAgentAndFormation($agent, $formation);
            if ($abonnement === null) $this->getAbonnementService()->ajouterAbonnement($agent, $formation);
        }

        $debut = $instance->getDebut();
        if ($debut !== null) {
            $dateRappel = DateTime::createFromFormat('d/m/Y H:i', $instance->getDebut() . " 08:00");
            $dateRappel->sub(new DateInterval('P4D'));
            $this->getRappelAgentAvantFormationService()->creer($instance, $dateRappel);
        } else {
           throw new RuntimeException("Aucun événement/rappel ne peut être créé sans au moins une séance de planifiée",0);
        }

        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function envoyerConvocation(FormationInstance $instance): FormationInstance
    {
        $this->getEtatInstanceService()->setEtatActif($instance,SessionEtats::ETAT_FORMATION_CONVOCATION);
        $this->update($instance);
        foreach ($instance->getListePrincipale() as $inscrit) {
            $this->getNotificationService()->triggerConvocation($inscrit);
        }
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function envoyerEmargement(FormationInstance $instance): FormationInstance
    {
        $this->update($instance);
        $this->getNotificationService()->triggerLienPourEmargement($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function demanderRetour(FormationInstance $instance): FormationInstance
    {
        $this->getEtatInstanceService()->setEtatActif($instance,SessionEtats::ETAT_ATTENTE_RETOURS);
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
        $this->getEtatInstanceService()->setEtatActif($instance,SessionEtats::ETAT_CLOTURE_INSTANCE);
        $this->update($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function annuler(FormationInstance $instance): FormationInstance
    {
        $this->getEtatInstanceService()->setEtatActif($instance,SessionEtats::ETAT_SESSION_ANNULEE);
        $this->update($instance);
        foreach ($instance->getInscrits() as $inscrit) {
            $this->getNotificationService()->triggerSessionAnnulee($inscrit);
            $agent = $inscrit->getAgent();
            $formation = $inscrit->getInstance()->getFormation();
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
        $this->getEtatInstanceService()->setEtatActif($instance,SessionEtats::ETAT_CREATION_EN_COURS);
        $this->update($instance);
        return $instance;
    }
    /** Fonction de classement des inscriptions ***********************************************************************/

    public function classerInscription(FormationInstanceInscrit $inscription): FormationInstanceInscrit
    {
        $session = $inscription->getInstance();
        $placePrincipale = $session->getPlaceDisponible(FormationInstanceInscrit::PRINCIPALE);
        try {
            if ($session->getNbPlacePrincipale() > $placePrincipale) {
                $inscription->setListe(FormationInstanceInscrit::PRINCIPALE);
                $this->getEntityManager()->flush($inscription);
                return $inscription;
            }
            $placeComplementaire = $session->getPlaceDisponible(FormationInstanceInscrit::COMPLEMENTAIRE);
            if ($session->getNbPlaceComplementaire() > $placeComplementaire) {
                $inscription->setListe(FormationInstanceInscrit::COMPLEMENTAIRE);
                $this->getEntityManager()->flush($inscription);
                return $inscription;
            }
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée.",0,$e);
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
}