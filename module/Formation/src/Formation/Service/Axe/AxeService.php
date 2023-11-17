<?php

namespace Formation\Service\Axe;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\Axe;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class AxeService {
    use ProvidesObjectManager;
    
    /** Gestion des entités ******************************************************************/

    public function create(Axe $axe): Axe
    {
        $this->getObjectManager()->persist($axe);
        $this->getObjectManager()->flush($axe);
        return $axe;
    }
    
    public function update(Axe $axe): Axe
    {
        $this->getObjectManager()->flush($axe);
        return $axe;
    }

    public function historise(Axe $axe): Axe
    {
        $axe->historiser();
        $this->getObjectManager()->flush($axe);
        return $axe;        
    }

    public function restore(Axe $axe): Axe
    {
        $axe->dehistoriser();
        $this->getObjectManager()->flush($axe);
        return $axe;
    }

    public function delete(Axe $axe): Axe
    {
        $this->getObjectManager()->remove($axe);
        $this->getObjectManager()->flush($axe);
        return $axe;
    }
    
    /** querying ******************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Axe::class)->createQueryBuilder('axe')
            ->leftJoin('axe.groupes', 'groupe')->addSelect('groupe')
            ->leftJoin('groupe.formations', 'formation')->addSelect('formation')
        ;
        return $qb;
    }

    public function getAxe(?int $id): ?Axe
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('axe.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Axe::class."] partagent le même id [".$id."]", 0, $e);
        }
        return $result;
    }

    public function getRequestedAxe(AbstractActionController $controller, string $param='axe'): ?Axe
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getAxe($id);
    }

    /** @return Axe[] */
    public function getAxes(string $champ='ordre', string $ordre='ASC', bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('axe.'.$champ, $ordre);
        if (!$withHisto) $qb = $qb->andWhere('axe.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getAxesAsOptions(string $champ='ordre', string $ordre='ASC', bool $withHisto = false): array
    {
        $axes = $this->getAxes($champ,$ordre,$withHisto);
        $options = [];
        foreach ($axes as $axe) {
            $options[$axe->getId()] = $axe->getLibelle();
        }
        return $options;
    }

    public function getAxeByLibelle(string $libelle, bool $withHisto = false) : ?Axe
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('axe.libelle = :libelle')->setParameter('libelle', $libelle);
        if (!$withHisto) $qb = $qb->andWhere('axe.histoDestruction IS NULL');
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Axe::class."] le même libellé [".$libelle."]", 0, $e);
        }
        return $result;
    }

    /** Facade ********************************************************************************************************/
}