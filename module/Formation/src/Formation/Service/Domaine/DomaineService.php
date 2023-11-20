<?php

namespace Formation\Service\Domaine;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\Domaine;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class DomaineService {
    use ProvidesObjectManager;
    
    /** Gestion des entités ******************************************************************/

    public function create(Domaine $domaine): Domaine
    {
        $this->getObjectManager()->persist($domaine);
        $this->getObjectManager()->flush($domaine);
        return $domaine;
    }
    
    public function update(Domaine $domaine): Domaine
    {
        $this->getObjectManager()->flush($domaine);
        return $domaine;
    }

    public function historise(Domaine $domaine): Domaine
    {
        $domaine->historiser();
        $this->getObjectManager()->flush($domaine);
        return $domaine;
    }

    public function restore(Domaine $domaine): Domaine
    {
        $domaine->dehistoriser();
        $this->getObjectManager()->flush($domaine);
        return $domaine;
    }

    public function delete(Domaine $domaine): Domaine
    {
        $this->getObjectManager()->remove($domaine);
        $this->getObjectManager()->flush($domaine);
        return $domaine;
    }
    
    /** querying ******************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Domaine::class)->createQueryBuilder('domaine')
            ->leftJoin('domaine.formations', 'formation')->addSelect('formation')
        ;
        return $qb;
    }

    public function getDomaine(?int $id): ?Domaine
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('domaine.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Domaine::class."] partagent le même id [".$id."]", 0, $e);
        }
        return $result;
    }

    public function getRequestedDomaine(AbstractActionController $controller, string $param='domaine'): ?Domaine
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getDomaine($id);
    }

    /** @return Domaine[] */
    public function getDomaines(string $champ='ordre', string $ordre='ASC', bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('domaine.'.$champ, $ordre);
        if (!$withHisto) $qb = $qb->andWhere('domaine.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getDomainesAsOptions(string $champ='ordre', string $ordre='ASC', bool $withHisto = false): array
    {
        $domaines = $this->getDomaines($champ,$ordre,$withHisto);
        $options = [];
        foreach ($domaines as $domaine) {
            $options[$domaine->getId()] = $domaine->getLibelle();
        }
        return $options;
    }

    public function getDomaineByLibelle(string $libelle, bool $withHisto = false) : ?Domaine
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('domaine.libelle = :libelle')->setParameter('libelle', $libelle);
        if (!$withHisto) $qb = $qb->andWhere('domaine.histoDestruction IS NULL');
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Domaine::class."] le même libellé [".$libelle."]", 0, $e);
        }
        return $result;
    }

    /** Facade ********************************************************************************************************/

    public function createDomaine(string $domaineLibelle): ?Domaine
    {
        $domaine = new Domaine();
        $domaine->setLibelle($domaineLibelle);
        $domaine->setOrdre(Domaine::MAX_ORDRE);
        $this->create($domaine);
        return $domaine;
    }

}