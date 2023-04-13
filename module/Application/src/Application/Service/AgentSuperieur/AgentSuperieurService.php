<?php

namespace Application\Service\AgentSuperieur;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentSuperieur;
use Application\Service\Agent\AgentServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;

class AgentSuperieurService
{
    use EntityManagerAwareTrait;
    use AgentServiceAwareTrait;

    /** GESTION DE L'ENTITE *******************************************************************************************/

    public function create(AgentSuperieur $agentSuperieur) : AgentSuperieur
    {
        try {
            $this->getEntityManager()->persist($agentSuperieur);
            $this->getEntityManager()->flush($agentSuperieur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $agentSuperieur;
    }

    public function update(AgentSuperieur $agentSuperieur) : AgentSuperieur
    {
        try {
            $this->getEntityManager()->flush($agentSuperieur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $agentSuperieur;
    }

    public function historise(AgentSuperieur $agentSuperieur) : AgentSuperieur
    {
        try {
            $agentSuperieur->historiser();
            $this->getEntityManager()->flush($agentSuperieur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $agentSuperieur;
    }

    public function restore(AgentSuperieur $agentSuperieur) : AgentSuperieur
    {
        try {
            $agentSuperieur->dehistoriser();
            $this->getEntityManager()->flush($agentSuperieur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $agentSuperieur;
    }

    public function delete(AgentSuperieur $agentSuperieur) : AgentSuperieur
    {
        try {
            $this->getEntityManager()->remove($agentSuperieur);
            $this->getEntityManager()->flush($agentSuperieur);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $agentSuperieur;
    }

    /** QUERRYING *****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(AgentSuperieur::class)->createQueryBuilder('agentsuperieur')
            ->join('agentsuperieur.agent', 'agent')->addSelect('agent')
            ->join('agentsuperieur.superieur', 'superieur')->addSelect('superieur')
        ;
        return $qb;
    }

    public function getAgentSuperieur(?int $id) : ?AgentSuperieur
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentsuperieur.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs AgentSuperieur partagent le même id ['.$id.']');
        }
        return $result;
    }

    public function getRequestedAgentSuperieur(AbstractActionController $controller, string $param='agent-superieur') : ?AgentSuperieur
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getAgentSuperieur($id);
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
    public function getAgentsSuperieursByAgent(Agent $agent, bool $histo = false, string $champ = 'id', $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentsuperieur.agent = :agent')->setParameter('agent', $agent)
            ->orderBy('agentsuperieur.' . $champ, $ordre);
        if ($histo === false) $qb = $qb->andWhere('agentsuperieur.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return AgentSuperieur[] */
    public function getAgentsSuperieursBySuperieur(Agent $superieur, bool $histo = false, string $champ = 'id', $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentsuperieur.superieur = :superieur')->setParameter('superieur', $superieur)
            ->orderBy('agentsuperieur.' . $champ, $ordre);
        if ($histo === false) $qb = $qb->andWhere('agentsuperieur.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function createAgentSuperieur(Agent $agent, Agent $superieur) : AgentSuperieur
    {
        $agentSuperieur = new AgentSuperieur();
        $agentSuperieur->setAgent($agent);
        $agentSuperieur->setSuperieur($superieur);
        $this->create($agentSuperieur);
        return $agentSuperieur;
    }

    /**
     * @return array
     */
    public function createAgentSuperieurWithArray(?Agent $agent, array $superieurIdArray, array $agents) : array
    {
        $result = []; $warning = [];
        if ($agent === null) throw new RuntimeException("Aucun agent de fourni");
        $superieurs = [];
        foreach ($superieurIdArray as $item) {
            if ($item !== '') {
                $superieur = $agents[$item];
                if ($superieur === null) $warning[] = "Aucun agent de connu avec l'identifiant [" . $item . "] (Agent = ".$agent->getId()."| Supérieur)";
                else $superieurs[] = $superieur;
            }
        }

        foreach ($agent->getSuperieurs() as $current) $this->historise($current);
        foreach ($superieurs as $superieur) {
            $agentsuperieur = $this->createAgentSuperieur($agent,$superieur);
            $result[] = $agentsuperieur;
        }
        return [
            'result' => $result,
            'warning' => $warning
        ];
    }

    public function historiseAll(?Agent $agent) : void
    {
        if ($agent !== null) {
            $superieurs = $this->getAgentsSuperieursByAgent($agent);
            foreach ($superieurs as $superieur) $this->historise($superieur);
        }
    }

    /**
     * @return User[]
     */
    public function getUsersInSuperieurs() : array
    {
        $qb = $this->getEntityManager()->getRepository(AgentSuperieur::class)->createQueryBuilder('asuperieur')
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
}