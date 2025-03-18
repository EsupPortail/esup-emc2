<?php

namespace Metier\Service\Domaine;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Metier\Entity\Db\Domaine;
use UnicaenApp\Exception\RuntimeException;

class DomaineService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

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

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Domaine::class)->createQueryBuilder('domaine')
            ->addSelect('famille')->leftJoin('domaine.familles', 'famille')
            ->addSelect('metier')->leftJoin('domaine.metiers', 'metier');
        return $qb;
    }

    /** @return Domaine[] */
    public function getDomaines(bool $withHisto = false, string $champ = 'libelle', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('domaine.' . $champ, $ordre);
        if (!$withHisto) $qb = $qb->andWhere('domaine.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Domaine[] */
    public function getDomainesAsOptions(bool $historiser = false): array
    {
        $domaines = $this->getDomaines();
        $options = [];
        foreach ($domaines as $domaine) {
            if ($historiser or $domaine->estNonHistorise())
                $options[$domaine->getId()] = $domaine->getLibelle();
        }
        return $options;
    }

    public function getDomainesAsJson(bool $asArray = false): string|array
    {
        $domaines = $this->getDomaines();
        $data = [];
        foreach ($domaines as $domaine) {
            $vh = match ($domaine->getTypeFonction()) {
                'support' => "<span class='badge' style='background:darkblue;'>Support</span>",
                'soutien' => "<span class='badge' style='background:darkgreen;'>Soutien</span>",
                default => "<span class='badge' style='background:gray;'>N.C.</span>",
            };
            $data[] = [
                'libellé' => $domaine->getLibelle(),
                'type-fonction' => $vh,
            ];
        }

        if ($asArray) return $data;
        return json_encode($data);
    }

    public function getDomaine(?int $id): ?Domaine
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('domaine.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . Domaine::class . "] partagent le même identifiant [" . $id . "]",0,$e);
        }
        return $result;
    }

    public function getRequestedDomaine(AbstractActionController $controller, string $paramName = 'domaine'): ?Domaine
    {
        $id = $controller->params()->fromRoute($paramName);
        $domaine = $this->getDomaine($id);

        return $domaine;
    }

    public function getDomaineByLibelle(string $libelle): ?Domaine
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('domaine.libelle = :libelle')->setParameter('libelle', $libelle)
            ->andWhere('domaine.histoDestruction IS NULL');
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . Domaine::class . "] partagent le même libellé [" . $libelle . "]",0,$e);
        }
        return $result;
    }

    public function createWith(string $domaineLibelle, bool $persist = true): ?Domaine
    {
        $domaine = new Domaine();
        $domaine->setLibelle($domaineLibelle);
        if ($persist) $this->create($domaine);
        return $domaine;
    }

}
