<?php

namespace Metier\Service\FamilleProfessionnelle;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Metier\Entity\Db\FamilleProfessionnelle;
use RuntimeException;

class FamilleProfessionnelleService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(FamilleProfessionnelle $famille): FamilleProfessionnelle
    {
        $this->getObjectManager()->persist($famille);
        $this->getObjectManager()->flush($famille);
        return $famille;
    }

    public function update(FamilleProfessionnelle $famille): FamilleProfessionnelle
    {
        $this->getObjectManager()->flush($famille);
        return $famille;
    }

    public function historise(FamilleProfessionnelle $famille): FamilleProfessionnelle
    {
        $famille->historiser();
        $this->getObjectManager()->flush($famille);
        return $famille;
    }

    public function restore(FamilleProfessionnelle $famille): FamilleProfessionnelle
    {
        $famille->dehistoriser();
        $this->getObjectManager()->flush($famille);
        return $famille;
    }

    public function delete(FamilleProfessionnelle $famille): FamilleProfessionnelle
    {
        $this->getObjectManager()->remove($famille);
        $this->getObjectManager()->flush($famille);
        return $famille;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(FamilleProfessionnelle::class)->createQueryBuilder('famille')
            ->addSelect('domaine')->leftJoin('famille.domaines', 'domaine')
            ->addSelect('metier')->leftJoin('domaine.metiers', 'metier');
        return $qb;
    }

    /** @return FamilleProfessionnelle[] */
    public function getFamillesProfessionnelles(): array
    {
        $qb = $this->createQueryBuilder()
            ->addOrderBy('famille.libelle');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return FamilleProfessionnelle[] */
    public function getFamillesProfessionnellesAsOptions(bool $historiser = false): array
    {
        $familles = $this->getFamillesProfessionnelles();
        $options = [];
        foreach ($familles as $famille) {
            if ($historiser or $famille->estNonHistorise())
                $options[$famille->getId()] = $famille->getLibelle();
        }
        return $options;
    }

    public function getFamilleProfessionnelle(?int $id): ?FamilleProfessionnelle
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('famille.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . FamilleProfessionnelle::class . "] partagent le même identifiant [" . $id . "]",0,$e);
        }
        return $result;
    }

    public function getRequestedFamilleProfessionnelle(AbstractActionController $controller, string $paramName = 'famille-professionnelle'): ?FamilleProfessionnelle
    {
        $id = $controller->params()->fromRoute($paramName);
        $famille = $this->getFamilleProfessionnelle($id);

        return $famille;
    }

    public function getFamilleProfessionnelleByLibelle(string $libelle): ?FamilleProfessionnelle
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('famille.libelle = :libelle')->setParameter('libelle', $libelle)
            ->andWhere('famille.histoDestruction IS NULL');

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . FamilleProfessionnelle::class . "] partagent le même libellé [" . $libelle . "]",0,$e);
        }
        return $result;
    }

    public function createWith(string $familleLibelle, bool $persist = true): ?FamilleProfessionnelle
    {
        $famille = new FamilleProfessionnelle();
        $famille->setLibelle($familleLibelle);
        if ($persist) $this->create($famille);
        return $famille;
    }
}
