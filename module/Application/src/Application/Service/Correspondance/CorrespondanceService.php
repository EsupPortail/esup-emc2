<?php

namespace Application\Service\Correspondance;

use Application\Entity\Db\Correspondance;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class CorrespondanceService {
    use EntityManagerAwareTrait;

    /** Pas de gestion des entités car les Correspondances sont importés depuis octopus **/

    /** TODO faire une methode pour forcer le rafraichissement depuis octopus ... */

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() {
        $qb = $this->getEntityManager()->getRepository(Correspondance::class)->createQueryBuilder('correspondance');
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Correspondance[]
     */
    public function getCorrespondances($champ = 'libelleLong', $ordre = 'ASC') {
        $qb = $this->createQueryBuilder()
            ->andWhere('correspondance.histo = :histo')
            ->setParameter('histo', 'O')
            ->orderBy('correspondance.' . $champ, $ordre)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Correspondance[]
     */
    public function getCorrespondancesHistorises($champ = 'libelleLong', $ordre = 'ASC') {
        $qb = $this->createQueryBuilder()
            ->andWhere('correspondance.histo != :histo')
            ->setParameter('histo', 'O')
            ->orderBy('correspondance.' . $champ, $ordre)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getCorrespondancesAsOptions()
    {
        $correspondances = $this->getCorrespondances();
        $options = [];
        foreach($correspondances as $correspondance) {
            $options[$correspondance->getId()] = $correspondance->getCategorie() . " - " . $correspondance->getLibelleLong();
        }
        return $options;
    }

    public function getCorrespondance($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('correspondance.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Correcpondance partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }
}
