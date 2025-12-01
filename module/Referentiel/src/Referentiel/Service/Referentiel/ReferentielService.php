<?php

namespace Referentiel\Service\Referentiel;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Referentiel\Entity\Db\Referentiel;
use RuntimeException;

class ReferentielService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Referentiel $referentiel): void
    {
        $this->getObjectManager()->persist($referentiel);
        $this->getObjectManager()->flush($referentiel);
    }

    public function update(Referentiel $referentiel): void
    {
        $this->getObjectManager()->flush($referentiel);
    }

    public function historise(Referentiel $referentiel): void
    {
        $referentiel->historiser();
        $this->getObjectManager()->flush($referentiel);
    }

    public function restore(Referentiel $referentiel): void
    {
        $referentiel->dehistoriser();
        $this->getObjectManager()->flush($referentiel);
    }

    public function delete(Referentiel $referentiel): void
    {
        $this->getObjectManager()->remove($referentiel);
        $this->getObjectManager()->flush($referentiel);
    }

    /** QUERYING ******************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Referentiel::class)->createQueryBuilder("referentiel");
        return $qb;
    }

    public function getReferentiel(?int $id): ?Referentiel
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('referentiel.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Referentiel::class."] partagent le même id [".$id."]",-1,$e);
        }
        return $result;
    }

    public function getRequestedReferentiel(AbstractActionController $controller, string $param = 'referentiel'): ?Referentiel
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getReferentiel($id);
        return $result;
    }

    public function getReferentielByLibelleCourt(?string $libelleCourt = null): ?Referentiel
    {
        $qb = $this->getObjectManager()->getRepository(Referentiel::class)->createQueryBuilder("referentiel")
            ->andWhere('referentiel.libelleCourt = :libelleCourt')->setParameter('libelleCourt', $libelleCourt)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Referentiel::class."] partagent le même libelleCourt [".$libelleCourt."]",-1,$e);
        }
        return $result;
    }

    /** @return Referentiel[] */
    public function getReferentiels(bool $withHisto = false): array
    {
        $qb = $this->getObjectManager()->getRepository(Referentiel::class)->createQueryBuilder("referentiel")
            ->orderBy('referentiel.libelleCourt', 'ASC');
        if (!$withHisto)  $qb = $qb->andWhere('referentiel.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getReferentielsAsOptions(bool $withHisto = false): array
    {
        $result = $this->getReferentiels($withHisto);
        $options = [];
        foreach ($result as $referentiel) {
            $options[$referentiel->getId()] = $this->optionify($referentiel);
        }
        return $options;
    }

    /** FACADE ********************************************************************************************************/

    public function optionify(Referentiel $referentiel): array
    {
        $this_option = [
            'value' => $referentiel->getId(),
            'attributes' => [
                'data-content' =>
                    "<span class='badge' style='background:" . $referentiel->getCouleur() . ";'>" . $referentiel->getLibelleCourt() . "</span> " . $referentiel->getLibelleLong(),
            ],
            'label' => $referentiel->getLibelleCourt(),
        ];
        return $this_option;
    }
}
