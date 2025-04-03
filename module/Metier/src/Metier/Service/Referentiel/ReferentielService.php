<?php

namespace Metier\Service\Referentiel;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Metier\Entity\Db\Referentiel;
use RuntimeException;

class ReferentielService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Referentiel $referentiel): Referentiel
    {
        $this->getObjectManager()->persist($referentiel);
        $this->getObjectManager()->flush($referentiel);
        return $referentiel;
    }

    public function update(Referentiel $referentiel): Referentiel
    {
        $this->getObjectManager()->flush($referentiel);
        return $referentiel;
    }

    public function historise(Referentiel $referentiel): Referentiel
    {
        $referentiel->historiser();
        $this->getObjectManager()->flush($referentiel);
        return $referentiel;
    }

    public function restore(Referentiel $referentiel): Referentiel
    {
        $referentiel->dehistoriser();
        $this->getObjectManager()->flush($referentiel);
        return $referentiel;
    }

    public function delete(Referentiel $referentiel): Referentiel
    {
        $this->getObjectManager()->remove($referentiel);
        $this->getObjectManager()->flush($referentiel);
        return $referentiel;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Referentiel::class)->createQueryBuilder('referentiel');

        return $qb;
    }

    /** @return Referentiel[] */
    public function getReferentiels(string $champ = 'libelleCourt', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('referentiel.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getReferentielsAsOptions(): array
    {
        $referentiels = $this->getReferentiels();
        $array = [];

        foreach ($referentiels as $referentiel) {
            $array[$referentiel->getId()] = $referentiel->getLibelleCourt() . " - " . $referentiel->getLibelleLong();
        }

        return $array;
    }

    public function getReferentiel(?int $id): ?Referentiel
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('referentiel.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs MetierReferentiel partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedReferentiel(AbstractActionController $controller, string $param = "referentiel"): ?Referentiel
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getReferentiel($id);

        return $result;
    }

    public function getReferentielByCode(string $referentielCode): ?Referentiel
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('referentiel.libelleCourt = :code')->setParameter('code', $referentielCode)
            ->andWhere('referentiel.histoDestruction IS NULL');

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . Referentiel::class . "] partagent le même code [" . $referentielCode . "]", 0, $e);
        }
        return $result;
    }

}