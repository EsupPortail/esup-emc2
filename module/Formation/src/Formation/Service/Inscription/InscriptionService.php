<?php

namespace Formation\Service\Inscription;

use Application\Entity\Db\Agent;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\Inscription;
use Formation\Entity\Db\Session;
use Formation\Entity\Db\StagiaireExterne;
use Formation\Provider\Etat\InscriptionEtats;
use Formation\Provider\Etat\SessionEtats;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenEtat\Entity\Db\EtatType;
use UnicaenUtilisateur\Entity\Db\UserInterface;

class InscriptionService
{
    use ProvidesObjectManager;

    /** Gestion des  entités ******************************************************************************************/

    public function create(Inscription $inscription): Inscription
    {
        $this->getObjectManager()->persist($inscription);
        $this->getObjectManager()->flush($inscription);
        return $inscription;
    }

    public function update(Inscription $inscription): Inscription
    {
        $this->getObjectManager()->flush($inscription);
        return $inscription;
    }

    public function historise(Inscription $inscription): Inscription
    {
        $inscription->historiser();
        $this->getObjectManager()->flush($inscription);
        return $inscription;
    }

    public function restore(Inscription $inscription): Inscription
    {
        $inscription->dehistoriser();
        $this->getObjectManager()->flush($inscription);
        return $inscription;
    }

    public function delete(Inscription $inscription): Inscription
    {
        $this->getObjectManager()->remove($inscription);
        $this->getObjectManager()->flush($inscription);
        return $inscription;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Inscription::class)->createQueryBuilder('inscription')
            ->join('inscription.session', 'session')->addSelect('session')
            ->join('session.formation', 'formation')->addSelect('formation')
            ->leftJoin('inscription.agent', 'agent')->addSelect('agent')
            ->leftJoin('inscription.stagiaire', 'stagiaire')->addSelect('stagiaire');
        return $qb;
    }

    /** @return Inscription[] */
    public function getInscriptions(string $champ = 'histoCreation', string $ordre = 'DESC', bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('inscription.' . $champ, $ordre);
        if (!$withHisto) $qb = $qb->andWhere('inscription.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getInscription(?int $id): ?Inscription
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('inscription.id = :id')->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . Inscription::class . "] partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedInscription(AbstractActionController $controller, string $param = 'inscription'): ?Inscription
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getInscription($id);
        return $result;
    }

    public function getInscriptionByUser(UserInterface $user): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agent.login = :login OR stagiaire.login = :login')
            ->setParameter('login', $user->getUsername())
            ->andWhere('inscription.histoDestruction IS NULL')
            ->leftJoin('session.formation', 'formation')->addSelect('formation')
            ->orderBy('formation.libelle', 'ASC');

        $qb = $qb
            ->leftJoin('session.etats', 'etat')->addSelect('etat')
            ->leftJoin('etat.type', 'etype')->addSelect('etype')
            ->andWhere('etat.histoDestruction IS NULL')
            ->andWhere('etype.code <> :code')->setParameter('code', SessionEtats::ETAT_CLOTURE_INSTANCE);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return  Inscription[] */
    public function getInscriptionsByAgent(Agent $agent): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('inscription.agent = :agent')->setParameter('agent', $agent)
            ->andWhere('inscription.histoDestruction IS NULL')
            ->leftJoin('session.formation', 'formation')->addSelect('formation')
            ->orderBy('formation.libelle', 'ASC');

//        $qb = $qb
//            ->leftJoin('session.etats', 'etat')->addSelect('etat')
//            ->leftJoin('etat.type', 'etype')->addSelect('etype')
//            ->andWhere('etat.histoDestruction IS NULL')
//            ->andWhere('etype.code <> :code')->setParameter('code', SessionEtats::ETAT_CLOTURE_INSTANCE);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @var Agent[] $agents
     * @return  Inscription[]
     */
    public function getInscriptionsByAgents(array $agents): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('inscription.agent in (:agents)')->setParameter('agents', $agents)
            ->andWhere('inscription.histoDestruction IS NULL')
            ->leftJoin('session.formation', 'formation')->addSelect('formation')
            ->orderBy('formation.libelle', 'ASC');

        $qb = $qb
            ->leftJoin('session.etats', 'etat')->addSelect('etat')
            ->leftJoin('etat.type', 'etype')->addSelect('etype')
            ->andWhere('etat.histoDestruction IS NULL')
            ->andWhere('etype.code <> :code')->setParameter('code', SessionEtats::ETAT_CLOTURE_INSTANCE);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Agent[] $agents
     * @param int|null $annee
     * @return Inscription[]
     */
    public function getInscriptionsValideesByAgents(array $agents, ?int $annee): array
    {
        $etats = [InscriptionEtats::ETAT_VALIDER_DRH, InscriptionEtats::ETAT_REFUSER];
        $result = $this->getInscriptionsByAgentsAndEtats($agents, $etats, $annee);
        return $result;
    }

    /**
     * @param StagiaireExterne[] $stagiaires
     * @param int|null $annee
     * @return Inscription[]
     */
    public function getInscriptionsValideesByStagiaires(array $stagiaires, ?int $annee): array
    {
        $etats = [InscriptionEtats::ETAT_VALIDER_DRH, InscriptionEtats::ETAT_REFUSER];
        $result = $this->getInscriptionsByStagiairesAndEtats($stagiaires, $etats, $annee);
        return $result;
    }

    /**
     * @param Agent[] $agents
     * @param int|null $annee
     * @return Inscription[]
     */
    public function getInscriptionsNonValideesByAgents(array $agents, ?int $annee): array
    {
        $etats = [InscriptionEtats::ETAT_DEMANDE, InscriptionEtats::ETAT_VALIDER_RESPONSABLE];
        $result = $this->getInscriptionsByAgentsAndEtats($agents, $etats, $annee);
        return $result;
    }

    /**
     * @param Agent[] $agents
     * @param EtatType[] $etats
     * @param int|null $annee
     * @return Inscription[]
     */
    public function getInscriptionsByAgentsAndEtats(array $agents, array $etats, ?int $annee): array
    {
        if ($annee === null) $annee = Formation::getAnnee();
        $debut = DateTime::createFromFormat('d/m/Y', '01/09/' . $annee);
        $fin = DateTime::createFromFormat('d/m/Y', '31/08/' . ($annee + 1));

        $qb = $this->createQueryBuilder()
            ->andWhere('inscription.histoDestruction IS NULL')
            ->andWhere('session.histoDestruction IS NULL')
            ->andWhere('agent in (:agents)')->setParameter('agents', $agents)
        ;

        $qb = Inscription::decorateWithEtatsCodes($qb, 'inscription', $etats);

        $result = $qb->getQuery()->getResult();
        $result = array_filter($result, function (Inscription $a) use ($debut, $fin) {
            $sessionDebut = DateTime::createFromFormat('d/m/Y', $a->getSession()->getDebut());
            $sessionFin = DateTime::createFromFormat('d/m/Y', $a->getSession()->getFin());
            return ($sessionDebut >= $debut && $sessionFin <= $fin);
        });
        return $result;
    }

    /**
     * @param StagiaireExterne[] $stagiaires
     * @param EtatType[] $etats
     * @param int|null $annee
     * @return Inscription[]
     */
    public function getInscriptionsByStagiairesAndEtats(array $stagiaires, array $etats, ?int $annee): array
    {
        if ($annee === null) $annee = Formation::getAnnee();
        $debut = DateTime::createFromFormat('d/m/Y', '01/09/' . $annee);
        $fin = DateTime::createFromFormat('d/m/Y', '31/08/' . ($annee + 1));

        $qb = $this->createQueryBuilder()
            ->andWhere('inscription.histoDestruction IS NULL')
            ->andWhere('session.histoDestruction IS NULL')
            ->andWhere('stagiaire in (:stagiaires)')->setParameter('stagiaires', $stagiaires)
        ;

        $qb = Inscription::decorateWithEtatsCodes($qb, 'inscription', $etats);

        $result = $qb->getQuery()->getResult();
        $result = array_filter($result, function (Inscription $a) use ($debut, $fin) {
            $sessionDebut = DateTime::createFromFormat('d/m/Y', $a->getSession()->getDebut());
            $sessionFin = DateTime::createFromFormat('d/m/Y', $a->getSession()->getFin());
            return ($sessionDebut >= $debut && $sessionFin <= $fin);
        });
        return $result;
    }

    public function getInscriptionsWithFiltre(array $params)
    {
        $qb = $this->createQueryBuilder()->orderBy('inscription.histoCreation', 'asc');

        if (isset($params['etat'])) {
            $qb = Inscription::decorateWithEtatsCodes($qb, 'inscription', [$params['etat']]);
        }
        if (isset($params['historise'])) {
            if ($params['historise'] === '1') $qb = $qb->andWhere('inscription.histoDestruction IS NOT NULL');
            if ($params['historise'] === '0') $qb = $qb->andWhere('inscription.histoDestruction IS NULL');
        }
        if (isset($params['annee']) and $params['annee'] !== '') {
            $annee = (int)$params['annee'];
            $debut = DateTime::createFromFormat('d/m/Y', '01/09/' . $annee);
            $fin = DateTime::createFromFormat('d/m/Y', '31/08/' . ($annee + 1));
            $qb = $qb
                ->leftJoin('session.journees', 'journee')->addSelect('journee')
                ->andWhere('journee.jour >= :debut')->setParameter('debut', $debut)
                ->andWhere('journee.jour <= :fin')->setParameter('fin', $fin);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Inscription[] */
    public function getInscriptionbyStagiaire(StagiaireExterne $stagiaire, bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('inscription.stagiaire = :stagiaire')->setParameter('stagiaire', $stagiaire)
        ;
        if (!$withHisto) $qb = $qb->andWhere('inscription.histoDestruction IS NULL');


        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Inscription[] */
    public function getInscriptionsByFormation(?Formation $formation): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('session.formation = :formation')->setParameter('formation', $formation)
            ->andWhere('inscription.histoDestruction IS NULL')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Inscription[] */
    public function getInscriptionsBySession(?Session $session): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('inscription.session = :session')->setParameter('session', $session)
            ->andWhere('inscription.histoDestruction IS NULL')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/


}