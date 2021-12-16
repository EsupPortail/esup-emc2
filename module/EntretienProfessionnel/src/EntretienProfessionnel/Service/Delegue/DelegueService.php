<?php

namespace EntretienProfessionnel\Service\Delegue;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Structure;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\Delegue;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Entity\Db\User;
use Zend\Mvc\Controller\AbstractActionController;

class DelegueService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Delegue $delegue
     * @return Delegue
     */
    public function create(Delegue $delegue) : Delegue
    {
        $this->createFromTrait($delegue);
        return $delegue;
    }

    /**
     * @param Delegue $delegue
     * @return Delegue
     */
    public function update(Delegue $delegue) : Delegue
    {
        $this->updateFromTrait($delegue);
        return $delegue;
    }

    /**
     * @param Delegue $delegue
     * @return Delegue
     */
    public function historise(Delegue $delegue) : Delegue
    {
        $this->historiserFromTrait($delegue);
        return $delegue;
    }

    /**
     * @param Delegue $delegue
     * @return Delegue
     */
    public function restore(Delegue $delegue) : Delegue
    {
        $this->restoreFromTrait($delegue);
        return $delegue;
    }

    /**
     * @param Delegue $delegue
     * @return Delegue
     */
    public function delete(Delegue $delegue) : Delegue
    {
        $this->deleteFromTrait($delegue);
        return $delegue;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Delegue::class)->createQueryBuilder('delegue')
            ->join('delegue.agent', 'agent')->addSelect('agent')
            ->join('delegue.structure', 'structure')->addSelect('structure')
            ->join('delegue.campagne', 'campagne')->addSelect('campagne')
        ;
        return $qb;
    }

    /**
     * @return Delegue[]
     */
    public function getDelegues() : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int|null $id
     * @return Delegue|null
     */
    public function getDelegue(?int $id) : ?Delegue
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('delegue.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Delegue partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Delegue|null
     */
    public function getRequestedDelegue(AbstractActionController $controller, string $param='delegue') : ?Delegue
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getDelegue($id);
        return $result;
    }

    /**
     * @param Structure $structure
     * @return Delegue[]
     */
    public function getDeleguesByStructure(Structure $structure) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('delegue.structure = :structure')
            ->setParameter('structure', $structure)
            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure $structure
     * @param Campagne $campagne
     * @return Delegue[]
     */
    public function getDeleguesByStructureAndCampagne(Structure $structure, Campagne $campagne) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('delegue.structure = :structure')
            ->setParameter('structure', $structure)
            ->andWhere('delegue.campagne = :campagne')
            ->setParameter('campagne', $campagne)
            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getDeleguesByAgent(Agent $agent) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('delegue.agent = :agent')
            ->setParameter('agent', $agent)
            ->orderBy('agent.nomUsuel, agent.prenom', 'ASC');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return User[]
     */
    public function getUsersInDelegue() : array
    {
        $delegues = $this->getDelegues();

        $users = [];
        foreach ($delegues as $delegue) {
            $user = $delegue->getAgent()->getUtilisateur();
            if ($user !== null) $users[$user->getId()] = $user;
        }
        return $users;
    }
}