<?php

namespace Carriere\Service\Corps;

use Agent\Entity\Db\AgentGrade;
use Agent\Provider\Role\RoleProvider as AgentRoleProvider;
use Agent\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Structure\Provider\Role\RoleProvider as StructureRoleProvider;
use Agent\Service\Agent\AgentServiceAwareTrait;
use Application\Service\SqlHelper\SqlHelperServiceAwareTrait;
use Carriere\Entity\Db\Corps;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\RoleInterface;
use UnicaenUtilisateur\Entity\Db\UserInterface;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class CorpsService
{
    use ProvidesObjectManager;
    use AgentServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use SqlHelperServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function update(Corps $corps): Corps
    {
        $this->getObjectManager()->flush($corps);
        return $corps;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Corps::class)->createQueryBuilder('corps')
            ->andWhere('corps.deletedOn IS NULL');
        return $qb;
    }

    /** @return Corps[] */
    public function getCorps(string $champ = 'libelleLong', string $ordre = 'ASC', bool $avecAgent = true, bool $avecHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('corps.' . $champ, $ordre);

        if ($avecAgent) {
            $qb = $qb->addSelect('agentGrade')->join('corps.agentGrades', 'agentGrade')
                ->addSelect('agent')->join('agentGrade.agent', 'agent')
                ->andWhere('agent.deletedOn IS NULL')
                ->andWhere('agentGrade.deletedOn IS NULL');
            $qb = AgentGrade::decorateWithActif($qb, 'agentGrade');
        }

        if ($avecHisto === false) {
            $qb = Corps::decorateWithActif($qb, 'corps');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getCorp(?int $id, bool $avecAgent = true): ?Corps
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('corps.id = :id')
            ->setParameter('id', $id);

        if ($avecAgent) {
            $qb = $qb->addSelect('agentGrade')->join('corps.agentGrades', 'agentGrade')
                ->addSelect('agent')->join('agentGrade.agent', 'agent')
                ->andWhere('agent.deletedOn IS NULL');
        }

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Corps partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedCorps(AbstractActionController $controller, string $param = 'corps'): ?Corps
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCorp((int)$id, false);
        return $result;
    }

    public function getCorpsAsOptions(string $champ = 'libelleLong', string $ordre = 'ASC', bool $avecAgent = false): array
    {
        $corps = $this->getCorps($champ, $ordre, $avecAgent);
        $options = [];
        foreach ($corps as $corp) {
            $options[$corp->getId()] = $corp->getLibelleCourt() . " - " . $corp->getLibelleLong();
        }
        return $options;
    }

    /** @return Corps[] */
    public function getCorpsByTerm(string $term): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('lower(corps.libelleCourt) LIKE :search or lower(corps.libelleLong) LIKE :search or lower(corps.code) LIKE :search')
            ->setParameter('search', '%' . strtolower($term) . '%')
//            ->andWhere('corps.histo IS NULL')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Corps[] $corps
     * @return array
     */
    public function formatCorpsJSON(array $corps): array
    {
        $result = [];
        foreach ($corps as $corp) {
            $result[] = array(
                'id' => $corp->getId(),
                'label' => $corp->getLibelleLong(),
                'extra' => "<span class='badge' style='background-color: slategray;'>" . $corp->getLibelleCourt() . "</span>",
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }

    /** Listing des agents ayant les corps ****************************************************************************/

    public function getRepartitionAgentsByConnectedUser(?UserInterface $user = null, ?RoleInterface $role = null): array
    {
        if ($user === null) $user = $this->getUserService()->getConnectedUser();
        if ($role === null) $role = $this->getUserService()->getConnectedRole();
        $agent = $this->getAgentService()->getAgentByUser($user);

        $params = [];
        $agent_subquery = <<<EOS
    select a.c_individu as agent_id
    from agent a
    where a.deleted_on is NULL

EOS;

        if ($role->getRoleId() === StructureRoleProvider::RESPONSABLE
            || $role->getRoleId() === StructureRoleProvider::GESTIONNAIRE
            || $role->getRoleId() === StructureRoleProvider::OBSERVATEUR
        ) {
            $structures = match($role->getRoleId()) {
                StructureRoleProvider::RESPONSABLE => $this->getStructureService()->getStructuresByResponsable($user),
                StructureRoleProvider::GESTIONNAIRE => $this->getStructureService()->getStructuresByGestionnaire($user),
                StructureRoleProvider::OBSERVATEUR => $this->getStructureService()->getStructuresByObservateur($user),
};
            $structure_ids = [];
            foreach ($structures as $structure) {
                $listing = $this->getStructureService()->getStructuresFilles($structure);
                foreach ($listing as $item) $structure_ids[$item->getId()] = $item->getId();
            }
            $structure_ids = "('".implode("','", $structure_ids)."')";
            $agent_subquery = <<<EOS
select DISTINCT(aca.agent_id) as agent_id
from agent_carriere_affectation aca
join structure s on s.id = aca.structure_id
join agent a on a.c_individu = aca.agent_id
where aca.deleted_on IS NULL
  and s.deleted_on IS NULL
  and a.deleted_on IS NULL
  and coalesce(aca.date_debut,now()) <= now()
  and coalesce(aca.date_fin,now()) >= now()
  and aca.structure_id in 
EOS;
            $agent_subquery .= $structure_ids;
        }

        if ($role->getRoleId() === AgentRoleProvider::ROLE_SUPERIEURE) {
            $agent_subquery = $this->getAgentSuperieurService()->generateQueryAgent();
            $params['superieur_id'] = $agent->getId();
        }
        if ($role->getRoleId() === AgentRoleProvider::ROLE_AUTORITE) {
            $agent_subquery = <<<EOS
    select DISTINCT aha.agent_id as agent_id
    from agent_hierarchie_autorite aha
    join agent a on aha.agent_id = a.c_individu
    where a.deleted_on is NULL
      and aha.deleted_on is NULL
      and coalesce(aha.date_debut,now()) <= now()
      and coalesce(aha.date_fin,now()) >= now()
      and aha.autorite_id = :autorite_id 

EOS;
            $params['autorite_id'] = $agent->getId();
        }

        $query = <<<EOS
WITH agents AS (

EOS;
        $query .= $agent_subquery;
        $query .= <<<EOS
)
select
      c.id as corps_id,
      count(DISTINCT acg.agent_id) as nb_agents,
      array_agg(DISTINCT acg.agent_id) as agents
from carriere_corps c
left join agent_carriere_grade acg on acg.corps_id = c.id
join agents a on a.agent_id = acg.agent_id
where acg.deleted_on is NULL
  and coalesce(acg.d_debut,now()) <= now()
  and coalesce(acg.d_fin,now()) >= now()
group by c.id;
EOS;
        $result = $this->getSqlHelperService()->executeQuery($query, $params);

        $repartition = [];
        foreach ($result as $item) {
            $repartition[$item['corps_id']] = $item['nb_agents'];
        }
        return $repartition;
    }
}
