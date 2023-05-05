<?php

namespace Application\Service\AgentAutorite;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAutorite;
use Application\Service\Agent\AgentServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;

class AgentAutoriteService
{
    use EntityManagerAwareTrait;
    use AgentServiceAwareTrait;

    /** GESTION DE L'ENTITE *******************************************************************************************/

    public function create(AgentAutorite $agentAutorite) : AgentAutorite
    {
        try {
            $this->getEntityManager()->persist($agentAutorite);
            $this->getEntityManager()->flush($agentAutorite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $agentAutorite;
    }

    public function update(AgentAutorite $agentAutorite) : AgentAutorite
    {
        try {
            $this->getEntityManager()->flush($agentAutorite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $agentAutorite;
    }

    public function historise(AgentAutorite $agentAutorite) : AgentAutorite
    {
        try {
            $agentAutorite->historiser();
            $this->getEntityManager()->flush($agentAutorite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $agentAutorite;
    }

    public function restore(AgentAutorite $agentAutorite) : AgentAutorite
    {
        try {
            $agentAutorite->dehistoriser();
            $this->getEntityManager()->flush($agentAutorite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $agentAutorite;
    }

    public function delete(AgentAutorite $agentAutorite) : AgentAutorite
    {
        try {
            $this->getEntityManager()->remove($agentAutorite);
            $this->getEntityManager()->flush($agentAutorite);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu en base de donnée",0,$e);
        }
        return $agentAutorite;
    }

    /** QUERRYING *****************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(AgentAutorite::class)->createQueryBuilder('agentautorite')
            ->join('agentautorite.agent', 'agent')->addSelect('agent')
//            ->leftjoin ('agent.grades','agrade')->addSelect('agrade')
//            ->leftjoin('agrade.bap', 'correspondance')->addSelect('correspondance')
//            ->leftjoin('correspondance.type', 'ctype')->addSelect('ctype')
//            ->leftJoin('agrade.grade', 'grade')->addSelect('grade')
//            ->leftJoin('agrade.corps', 'corps')->addSelect('corps')
//            ->leftJoin('agent.affectations', 'affectation')->addSelect('affectation')
//            ->leftJoin('affectation.structure', 'structure')->addSelect('structure')
            ->join('agentautorite.autorite', 'autorite')->addSelect('autorite')
        ;
        return $qb;
    }

    public function getAgentAutorite(?int $id) : ?AgentAutorite
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentautorite.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs AgentAutorite partagent le même id ['.$id.']');
        }
        return $result;
    }

    public function getRequestedAgentAutorite(AbstractActionController $controller, string $param='agent-autorite') : ?AgentAutorite
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getAgentAutorite($id);
        return $result;
    }

    /** @return AgentAutorite[] */
    public function getAgentsAutorites(bool $histo = false, string $champ = 'id', $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('agentautorite.' . $champ, $ordre);
        if ($histo === false) $qb = $qb->andWhere('agentautorite.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return AgentAutorite[] */
    public function getAgentsAutoritesByAgent(Agent $agent, bool $histo = false, string $champ = 'id', $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentautorite.agent = :agent')->setParameter('agent', $agent)
            ->orderBy('agentautorite.' . $champ, $ordre);
        if ($histo === false) $qb = $qb->andWhere('agentautorite.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return AgentAutorite[] */
    public function getAgentsAutoritesByAutorite(Agent $autorite, bool $histo = false, string $champ = 'id', $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentautorite.autorite = :autorite')->setParameter('autorite', $autorite)
            ->orderBy('agentautorite.' . $champ, $ordre);
        if ($histo === false) $qb = $qb->andWhere('agentautorite.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function createAgentAutorite(Agent $agent, Agent $autorite) : AgentAutorite
    {
        $agentAutorite = new AgentAutorite();
        $agentAutorite->setAgent($agent);
        $agentAutorite->setAutorite($autorite);
        $this->create($agentAutorite);
        return $agentAutorite;
    }

    public function createAgentAutoriteWithArray(?Agent $agent, array $autoriteIdArray, array $agents) : array
    {
        $result = []; $warning = [];
        if ($agent === null) throw new RuntimeException("Aucun agent de fourni");
        $autorites = [];
        foreach ($autoriteIdArray as $item) {
            if ($item !== '') {
                $autorite = $agents[$item];
                if ($autorite === null) $warning[] = "Aucun agent de connu avec l'identifiant [" . $item . "] (Agent = ".$agent->getId()."| Autorité)";
                else $autorites[] = $autorite;
            }
        }

        foreach ($agent->getAutorites() as $current) $this->historise($current);
        foreach ($autorites as $autorite) {
            $agentautorite = $this->createAgentAutorite($agent,$autorite);
            $result[] = $agentautorite;
        }
        return [
            'result' => $result,
            'warning' => $warning
        ];
    }

    public function historiseAll(?Agent $agent) : void
    {
        if ($agent !== null) {
            $autorites = $this->getAgentsAutoritesByAgent($agent);
            foreach ($autorites as $autorite) $this->historise($autorite);
        }
    }

    /**
     * @return User[]
     */
    public function getUsersInAutorites() : array
    {
        $qb = $this->getEntityManager()->getRepository(AgentAutorite::class)->createQueryBuilder('aautorite')
            ->join('aautorite.autorite', 'agent')
            ->join('agent.utilisateur', 'utilisateur')
            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC')
        ;
        $result = $qb->getQuery()->getResult();

        $users = [];
        /** @var AgentAutorite $item */
        foreach ($result as $item) {
            $users[$item->getAutorite()->getId()] = $item->getAutorite()->getUtilisateur();
        }
        return $users;
    }


    public function isAutorite(Agent $agent, Agent $autorite) : bool
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('agentautorite.agent = :agent')->setParameter('agent',$agent)
            ->andWhere('agentautorite.autorite = :autorite')->setParameter('autorite', $autorite)
            ->andWhere('agentautorite.histoDestruction IS NULL')
        ;
        $result = $qb->getQuery()->getResult();
        return !empty($result);
    }
}