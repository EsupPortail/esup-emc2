<?php

namespace Formation\Service\FormationInstance;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use DateInterval;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Provider\Etat\SessionEtats;
use Formation\Service\Abonnement\AbonnementServiceAwareTrait;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenDbImport\Entity\Db\Service\Source\SourceServiceAwareTrait;
use UnicaenDbImport\Entity\Db\Source;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class FormationInstanceService
{
    use EntityManagerAwareTrait;
    use AbonnementServiceAwareTrait;
    use EtatServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use SourceServiceAwareTrait;


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
        $qb = $this->getEntityManager()->getRepository(FormationInstance::class)->createQueryBuilder('Finstance')
            ->addSelect('formation')->join('Finstance.formation', 'formation')
            ->addSelect('journee')->leftJoin('Finstance.journees', 'journee')
            ->addSelect('inscrit')->leftJoin('Finstance.inscrits', 'inscrit')
            ->addSelect('frais')->leftJoin('inscrit.frais', 'frais')
            ->addSelect('agent')->leftJoin('inscrit.agent', 'agent')
            ->addSelect('affectation')->leftJoin('agent.affectations', 'affectation')
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
            ->addSelect('etat')->leftjoin('Finstance.etat', 'etat')
            ->addSelect('etype')->leftjoin('etat.type', 'etype');
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return FormationInstance[]
     */
    public function getFormationsInstances(string $champ = 'id', string $ordre = 'ASC'): array
    {
        $qb = $this->getEntityManager()->getRepository(FormationInstance::class)->createQueryBuilder('Finstance')
            ->join('Finstance.source', 'source')->addSelect('source')
            ->orderBy('Finstance.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Formation $formation
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getFormationsInstancesByFormation(Formation $formation, string $champ = 'id', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->addSelect('finstance')->leftJoin('formation.instances', 'finstance')
            ->andWhere('formation.id = :id')
            ->setParameter('id', $formation->getId())
            ->orderBy('Finstance.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $etatCode
     * @return FormationInstance[]
     */
    public function getFormationsInstancesByEtat(string $etatCode): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etat.code = :code')
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

    /**
     * @param Source $source
     * @param string $idSource
     * @return FormationInstance|null
     */
    public function getFormationInstanceBySource( $source,  $idSource): ?FormationInstance
    {

        if (!($source instanceof  Source)) {
            var_dump($source);
        }
        $qb = $this->createQueryBuilder()
            ->andWhere('Finstance.source = :source')
            ->andWhere('Finstance.idSource = :idSource')
            ->setParameter('source', $source)
            ->setParameter('idSource', $idSource);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationInstance partagent le même idSource [" . $source . "-" . $idSource . "]", 0, $e);
        }
        return $result;
    }

    /** FACADE  *******************************************************************************************************/

    public function createNouvelleInstance(Formation $formation): FormationInstance
    {
        $instance = new FormationInstance();
        $instance->setType(FormationInstance::TYPE_INTERNE);
        $instance->setAutoInscription(true);
        $instance->setNbPlacePrincipale($this->getParametreService()->getParametreByCode('FORMATION', 'NB_PLACE_PRINCIPALE')->getValeur());
        $instance->setNbPlaceComplementaire($this->getParametreService()->getParametreByCode('FORMATION', 'NB_PLACE_COMPLEMENTAIRE')->getValeur());
        $instance->setFormation($formation);
        $instance->setEtat($this->getEtatService()->getEtatByCode(SessionEtats::ETAT_CREATION_EN_COURS));

        $this->create($instance);
        /** @var Source  $source */
        $source = $this->sourceService->getRepository()->findOneBy(['code' => HasSourceInterface::SOURCE_EMC2]);
        $instance->setSource($source);
        $instance->setIdSource(($formation->getIdSource()) ? (($formation->getIdSource()) . "-" . $instance->getId()) : ($formation->getId() . "-" . $instance->getId()));
        $this->update($instance);

        return $instance;
    }

    /** Fonctions associées aux états de l'instance *******************************************************************/

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function ouvrirInscription(FormationInstance $instance): FormationInstance
    {
        $instance->setEtat($this->getEtatService()->getEtatByCode(SessionEtats::ETAT_INSCRIPTION_OUVERTE));
        $this->update($instance);

        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function fermerInscription(FormationInstance $instance): FormationInstance
    {
        $instance->setEtat($this->getEtatService()->getEtatByCode(SessionEtats::ETAT_INSCRIPTION_FERMEE));
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
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function envoyerConvocation(FormationInstance $instance): FormationInstance
    {
        $instance->setEtat($this->getEtatService()->getEtatByCode(SessionEtats::ETAT_FORMATION_CONVOCATION));
        $this->update($instance);
        foreach ($instance->getListePrincipale() as $inscrit)
        {
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
        $instance->setEtat($this->getEtatService()->getEtatByCode(SessionEtats::ETAT_ATTENTE_RETOURS));
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
        $instance->setEtat($this->getEtatService()->getEtatByCode(SessionEtats::ETAT_CLOTURE_INSTANCE));
        $this->update($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function annuler(FormationInstance $instance): FormationInstance
    {
        $instance->setEtat($this->getEtatService()->getEtatByCode(SessionEtats::ETAT_SESSION_ANNULEE));
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
        $instance->setEtat($this->getEtatService()->getEtatByCode(SessionEtats::ETAT_CREATION_EN_COURS));
        $this->update($instance);
        return $instance;
    }
    /** Fonction de classement des inscriptions ***********************************************************************/

    public function classerInscription(FormationInstanceInscrit $inscription) : FormationInstanceInscrit
    {
        $session = $inscription->getInstance();
        $placePrincipale = $session->getPlaceDisponible(FormationInstanceInscrit::PRINCIPALE);
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
        return $inscription;
    }


    /**
     * @return FormationInstance[]
     * @attention se base sur l'etat !
     */
    public function getFormationInstanceEnCours(): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('Finstance.etat IS NOT NULL')
            ->andWhere('etat.code <> :annuler AND etat.code <> :cloturer')
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
            ->andWhere('formation.affichage = :true')->setParameter('true', true)
        ;
        $result = $qb->getQuery()->getResult();

        return $result;
    }


}