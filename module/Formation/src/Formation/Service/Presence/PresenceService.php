<?php

namespace Formation\Service\Presence;

use Doctrine\DBAL\Driver\Exception as DRV_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\Inscription;
use Formation\Entity\Db\Presence;
use Formation\Entity\Db\Seance;
use Formation\Entity\Db\Session;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;

class PresenceService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Presence $presence
     * @return Presence
     */
    public function create(Presence $presence): Presence
    {
        $this->getObjectManager()->persist($presence);
        $this->getObjectManager()->flush($presence);
        return $presence;
    }

    /**
     * @param Presence $presence
     * @return Presence
     */
    public function update(Presence $presence): Presence
    {
        $this->getObjectManager()->flush($presence);
        return $presence;
    }

    /**
     * @param Presence $presence
     * @return Presence
     */
    public function historise(Presence $presence): Presence
    {
        $presence->historiser();
        $this->getObjectManager()->flush($presence);
        return $presence;
    }

    /**
     * @param Presence $presence
     * @return Presence
     */
    public function restore(Presence $presence): Presence
    {
        $presence->dehistoriser();
        $this->getObjectManager()->flush($presence);
        return $presence;
    }

    /**
     * @param Presence $presence
     * @return Presence
     */
    public function delete(Presence $presence): Presence
    {
        $this->getObjectManager()->remove($presence);
        $this->getObjectManager()->flush($presence);
        return $presence;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Presence::class)->createQueryBuilder('presence')
            ->addSelect('journee')->join('presence.journee', 'journee')
            ->addSelect('finstance')->join('journee.instance', 'finstance')
            ->addSelect('formation')->join('finstance.formation', 'formation')
            ->addSelect('inscription')->join('presence.inscription', 'inscription');
        return $qb;
    }

    /**
     * @param integer|null $id
     * @return Presence|null
     */
    public function getPresence(?int $id): ?Presence
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('presence.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Presence partagent le même id [" . $id . "].", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Presence|null
     */
    public function getRequestedPresence(AbstractActionController $controller, string $param = 'presence'): ?Presence
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getPresence($id);
    }

    public function getPresenceByJourneeAndInscription(Seance $journee, Inscription $inscription): ?Presence
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('presence.journee = :journee')
            ->andWhere('presence.inscription = :inscription')
            ->setParameter('journee', $journee)
            ->setParameter('inscription', $inscription)
            ->andWhere('presence.histoDestruction IS NULL');

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Presence (non historisées) partagent la même journée [" . $journee->getId() . "] et le même inscrit [" . $inscription->getId() . "]", 0, $e);
        }
        return $result;
    }

    /**
     * @param Session $session
     * @return Presence[]
     */
    public function getPresenceBySession(Session $session): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('journee.instance = :session')
            ->setParameter('session', $session);

        return $qb->getQuery()->getResult();

    }

    /**
     * @return Presence[]
     */
    public function getPresences(): array
    {
        $qb = $this->getObjectManager()->getRepository(Presence::class)->createQueryBuilder('presence');
        $result = $qb->getQuery()->getResult();
        return $result;
    }


    public function getPresencesManquantes(?Session $session): array
    {
        $sql = <<<EOS
select presence.id, coalesce(concat(agent.prenom, ' ', coalesce(agent.nom_usage, agent.nom_famille)), concat(stagiaire.prenom, ' ', stagiaire.nom)) AS personne, seance.id, inscription.liste, presence.statut
from formation_instance session
left join formation_inscription inscription on session.id = inscription.session_id
left join agent on inscription.agent_id = agent.c_individu
left join formation_stagiaire_externe stagiaire on inscription.stagiaire_id = stagiaire.id
left join formation_seance seance on session.id = seance.instance_id
left join public.formation_presence presence on seance.id = presence.journee_id and inscription.id = presence.inscription_id
where true
    -- pas histo --
AND    inscription.histo_destruction IS NULL
AND    seance.histo_destruction IS NULL
    -- selection --
AND session.id=:session_id
AND inscription.liste = 'principale'
AND (presence.statut IS NULL OR presence.statut = 'NON_RENSEIGNEE')
EOS;

        try {
            $res = $this->getObjectManager()->getConnection()->executeQuery($sql, ['session_id' => $session->getId()]);
            try {
                $tmp = $res->fetchAllAssociative();
            } catch (DRV_Exception $e) {
                throw new RuntimeException("Un problème est survenue lors de la récupération des fonctions d'un groupe d'individus", 0, $e);
            }
        } catch (DBA_Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des fonctions d'un groupe d'individus", 0, $e);
        }

        return $tmp;
    }

    /** Facade ********************************************************************************************************/

    /** QUID :: j'ai oublie mode a vérifier ce que c'est */
    public function createWith(Inscription $inscription, Seance $seance, string $mode, string $presenceType = "???"): Presence
    {
        $presence = new Presence();
        $presence->setJournee($seance);
        $presence->setInscription($inscription);
        $presence->setStatut($mode);
        $presence->setPresenceType($presenceType);
        $this->create($presence);
        return $presence;
    }
}