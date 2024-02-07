<?php

namespace Formation\Service\Stagiaire;

use Application\Service\Agent\AgentServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\LAGAFStagiaire;
use RuntimeException;

class StagiaireService {
    use ProvidesObjectManager;
    use AgentServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param LAGAFStagiaire $stagiaire
     * @return LAGAFStagiaire
     */

    public function create(LAGAFStagiaire $stagiaire) : LAGAFStagiaire {
        $this->getObjectManager()->persist($stagiaire);
        $this->getObjectManager()->flush($stagiaire);
        return $stagiaire;
    }

    /**
     * @param LAGAFStagiaire $stagiaire
     * @return LAGAFStagiaire
     */

    public function update(LAGAFStagiaire $stagiaire) : LAGAFStagiaire {
        $this->getObjectManager()->flush($stagiaire);
        return $stagiaire;
    }

    /**
     * @param LAGAFStagiaire $stagiaire
     * @return LAGAFStagiaire
    */

    public function delete(LAGAFStagiaire $stagiaire) : LAGAFStagiaire {
        $this->getObjectManager()->remove($stagiaire);
        $this->getObjectManager()->flush($stagiaire);
        return $stagiaire;
    }

    /**
     * @return LAGAFStagiaire[]
     */
    public function getStagiaires() : array
    {
        $qb = $this->getObjectManager()->getRepository(LAGAFStagiaire::class)->createQueryBuilder('stagiaires');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getStagiaire(int $id) : LAGAFStagiaire
    {
        $qb = $this->getObjectManager()->getRepository(LAGAFStagiaire::class)->createQueryBuilder('stagiaire')
            ->andWhere('stagiaire.nStagiaire = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs StagiaireLagaf avec le mêm id [".$id."]",0,$e);
        }
        return $result;
    }
}