<?php

namespace Formation\Service\Stagiaire;

use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Entity\Db\LAGAFStagiaire;
use UnicaenApp\Service\EntityManagerAwareTrait;

class StagiaireService {
    use EntityManagerAwareTrait;
    use AgentServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param LAGAFStagiaire $stagiaire
     * @return LAGAFStagiaire
     */

    public function create(LAGAFStagiaire $stagiaire) : LAGAFStagiaire {
        $this->getEntityManager()->persist($stagiaire);
        $this->getEntityManager()->flush($stagiaire);
        return $stagiaire;
    }

    /**
     * @param LAGAFStagiaire $stagiaire
     * @return LAGAFStagiaire
     */

    public function update(LAGAFStagiaire $stagiaire) : LAGAFStagiaire {
        $this->getEntityManager()->flush($stagiaire);
        return $stagiaire;
    }

    /**
     * @param LAGAFStagiaire $stagiaire
     * @return LAGAFStagiaire
    */

    public function delete(LAGAFStagiaire $stagiaire) : LAGAFStagiaire {
        $this->getEntityManager()->remove($stagiaire);
        $this->getEntityManager()->flush($stagiaire);
        return $stagiaire;
    }

    /**
     * @return LAGAFStagiaire[]
     */
    public function getStagiaires() : array
    {
        $qb = $this->getEntityManager()->getRepository(LAGAFStagiaire::class)->createQueryBuilder('stagiaires');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getStagiaire(int $id) : LAGAFStagiaire
    {
        $qb = $this->getEntityManager()->getRepository(LAGAFStagiaire::class)->createQueryBuilder('stagiaire')
            ->andWhere('stagiaire.nStagiaire = :id')->setParameter('id', $id);
        $result = $qb->getQuery()->getOneOrNullResult();
        return $result;
    }
}