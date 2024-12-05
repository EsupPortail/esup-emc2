<?php

namespace Application\Service\AgentSuperieur;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentSuperieur;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use RuntimeException;
use UnicaenUtilisateur\Entity\Db\User;

class AgentSuperieurService
{
    use ProvidesObjectManager;
    use AgentServiceAwareTrait;

    /** GESTION DE L'ENTITE *******************************************************************************************/

    public function create(AgentSuperieur $agentSuperieur) : AgentSuperieur
    {
        $this->getObjectManager()->persist($agentSuperieur);
        $this->getObjectManager()->flush($agentSuperieur);
        return $agentSuperieur;
    }

    public function update(AgentSuperieur $agentSuperieur) : AgentSuperieur
    {
        $this->getObjectManager()->flush($agentSuperieur);
        return $agentSuperieur;
    }

    public function historise(AgentSuperieur $agentSuperieur) : AgentSuperieur
    {
        $agentSuperieur->historiser();
        $this->getObjectManager()->flush($agentSuperieur);
        return $agentSuperieur;
    }

    public function restore(AgentSuperieur $agentSuperieur) : AgentSuperieur
    {
        $agentSuperieur->dehistoriser();
        $this->getObjectManager()->flush($agentSuperieur);
        return $agentSuperieur;
    }

    public function delete(AgentSuperieur $agentSuperieur) : AgentSuperieur
    {
        $this->getObjectManager()->remove($agentSuperieur);
        $this->getObjectManager()->flush($agentSuperieur);
        return $agentSuperieur;
    }

    /** QUERRYING *****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(AgentSuperieur::class)->createQueryBuilder('agentsuperieur')
            ->join('agentsuperieur.agent', 'agent')->addSelect('agent')
            ->join('agentsuperieur.superieur', 'superieur')->addSelect('superieur')
        ;
        return $qb;
    }

    public function getAgentSuperieur(?string $id) : ?AgentSuperieur
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentsuperieur.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs AgentSuperieur partagent le même id ['.$id.']',0,$e);
        }
        return $result;
    }

    /** @return AgentSuperieur[] */
    public function getAgentsSuperieurs(bool $histo = false, string $champ = 'id', $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('agentsuperieur.' . $champ, $ordre);
        if ($histo === false) $qb = $qb->andWhere('agentsuperieur.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return AgentSuperieur[] */
    public function getAgentsSuperieursByAgent(?Agent $agent, bool $histo = false, string $champ = 'id', $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentsuperieur.agent = :agent')->setParameter('agent', $agent)
            ->andWhere('agent.deletedOn IS NULL')
            ->orderBy('agentsuperieur.' . $champ, $ordre);
        if ($histo === false) $qb = $qb->andWhere('agentsuperieur.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return AgentSuperieur[] */
    public function getAgentsSuperieursBySuperieur(?Agent $superieur, bool $histo = false, string $champ = 'id', $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentsuperieur.superieur = :superieur')->setParameter('superieur', $superieur)
            ->orderBy('agentsuperieur.' . $champ, $ordre);
        if ($histo === false) $qb = $qb->andWhere('agentsuperieur.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Agent[] */
    public function getAgentsWithSuperieur(?Agent $superieur, DateTime $dateDebut = null, DateTime $dateFin = null): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agent.deletedOn IS NULL')
            ->andWhere('agentsuperieur.superieur = :superieur')->setParameter('superieur', $superieur)
            ->andWhere('agentsuperieur.histoCreation IS NULL OR agentsuperieur.histoCreation < :fin')->setParameter('fin', $dateFin)
            ->andWhere('agentsuperieur.histoDestruction IS NULL OR agentsuperieur.histoDestruction > :debut')->setParameter('debut', $dateDebut);

        $result = $qb->getQuery()->getResult();

        $agents = [];
        foreach ($result as $item) {
            $agent = $item->getAgent();
            $agents[$agent->getId()] = $agent;
        }
        return $agents;
    }

    /** @return Agent[] */
    public function getAgentsWithSuperieurAndTerm(Agent $superieur, string $term): array
    {
        $qb = $this->createQueryBuilder();
        //superieur
        $qb = $qb   ->andWhere('agent.deletedOn IS NULL')
                    ->andWhere('agentsuperieur.superieur = :superieur')->setParameter('superieur', $superieur)
                    ->andWhere('agentsuperieur.histoDestruction IS NULL')
        ;
        //term
        $qb = $qb   ->andWhere("LOWER(CONCAT(agent.nomUsuel, ' ', agent.prenom)) like :search OR LOWER(CONCAT(agent.prenom, ' ', agent.nomUsuel)) like :search")
        ->setParameter('search', '%'.strtolower($term).'%')
        ->orderBy("concat(agent.nomUsuel, ' ', agent.prenom)", 'ASC');

        $result = $qb->getQuery()->getResult();
        return array_map(function (AgentSuperieur $agentSuperieur) { return $agentSuperieur->getAgent(); }, $result);
    }

    /** FACADE ********************************************************************************************************/

    /**
     * @param array{Agent, Agent, DateTime, ?Datetime} $chaine
     */
    public function createAgentSuperieurWithArray(array $chaine): void
    {
        $superieur = new AgentSuperieur();
        $superieur->setAgent($chaine[0]);
        $superieur->setSuperieur($chaine[1]);
        $superieur->setDateDebut($chaine[2]);
        if ($chaine[3]) {
            $superieur->setDateFin($chaine[3]);
        }
        $id = $superieur->generateId();
        $superieur->setId($id);
        $superieur->setCreatedOn(new DateTime());

        $this->create($superieur);
    }

    public function historiseAll(?Agent $agent) : void
    {
        if ($agent !== null) {
            $superieurs = $this->getAgentsSuperieursByAgent($agent);
            foreach ($superieurs as $superieur) $this->historise($superieur);
        }
    }

    /** @return User[] */
    public function getUsersInSuperieurs() : array
    {
        $qb = $this->getObjectManager()->getRepository(AgentSuperieur::class)->createQueryBuilder('asuperieur')
            ->join('asuperieur.superieur', 'agent')
            ->join('agent.utilisateur', 'utilisateur')
            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC')
        ;
        $result = $qb->getQuery()->getResult();

        $users = [];
        /** @var AgentSuperieur $item */
        foreach ($result as $item) {
            $users[$item->getSuperieur()->getId()] = $item->getSuperieur()->getUtilisateur();
        }
        return $users;
    }

    public function isSuperieur(Agent $agent, Agent $superieur) : bool
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentsuperieur.agent = :agent')->setParameter('agent',$agent)
            ->andWhere('agentsuperieur.superieur = :superieur')->setParameter('superieur', $superieur)
            ->andWhere('agentsuperieur.histoDestruction IS NULL')
        ;
        $result = $qb->getQuery()->getResult();
        return !empty($result);
    }
}