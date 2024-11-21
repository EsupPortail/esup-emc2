<?php

namespace FicheReferentiel\Service\FicheReferentiel;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheReferentiel\Entity\Db\FicheReferentiel;
use Laminas\Mvc\Controller\AbstractActionController;
use Metier\Entity\Db\Metier;
use Metier\Entity\Db\Referentiel;
use RuntimeException;

class FicheReferentielService {

    use ProvidesObjectManager;

    /** Gestion des entités *******************************************************************************************/

    public function create(FicheReferentiel $ficheReferentiel): FicheReferentiel
    {
        $this->getObjectManager()->persist($ficheReferentiel);
        $this->getObjectManager()->flush($ficheReferentiel);
        return $ficheReferentiel;
    }

    public function update(FicheReferentiel $ficheReferentiel): FicheReferentiel
    {
        $this->getObjectManager()->flush($ficheReferentiel);
        return $ficheReferentiel;
    }

    public function historise($ficheReferentiel): FicheReferentiel
    {
        $ficheReferentiel->historiser();
        $this->getObjectManager()->flush($ficheReferentiel);
        return $ficheReferentiel;
    }

    public function restore($ficheReferentiel): FicheReferentiel
    {
        $ficheReferentiel->dehistoriser();
        $this->getObjectManager()->flush($ficheReferentiel);
        return $ficheReferentiel;
    }

    public function delete($ficheReferentiel): FicheReferentiel
    {
        $this->getObjectManager()->remove($ficheReferentiel);
        $this->getObjectManager()->flush($ficheReferentiel);
        return $ficheReferentiel;
    }

    /** Requetages ****************************************************************************************************/

    public function createQueryBuilder(bool $joinCompetece = false): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(FicheReferentiel::class)->createQueryBuilder('fichereferentiel')
            ->join('fichereferentiel.metier', 'metier')->addSelect('metier')
            ->join('fichereferentiel.referentiel', 'referentiel')->addSelect('referentiel')
        ;

        if ($joinCompetece) {
            $qb = $qb
                ->leftJoin('fichereferentiel.competences', 'competenceelement')->addSelect('competenceelement')
                ->leftJoin('competenceelement.competence', 'competence')->addSelect('competence')
                ->leftJoin('competenceelement.niveau', 'niveau')->addSelect('niveau')
            ;
        }

        return $qb;
    }

    /** @return FicheReferentiel[] */
    public function getFichesReferentiels(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder();
        if (!$withHisto) $qb = $qb->andWhere('fichereferentiel.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFicheReferentiel(?int $id): ?FicheReferentiel
    {
        $qb = $this->createQueryBuilder(true)
            ->andWhere('fichereferentiel.id = :id')->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".FicheReferentiel::class."] partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedFicheReferentiel(AbstractActionController $controller, string $param = 'fiche-referentiel'): ?FicheReferentiel
    {
        $id  = $controller->params()->fromRoute($param);
        $result = $this->getFicheReferentiel($id);
        return $result;
    }

    /** @return FicheReferentiel[] */
    public function getFichesReferentielsByReferentiel(Referentiel $referentiel): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('fichereferentiel.referentiel = :referentiel')->setParameter('referentiel', $referentiel);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return FicheReferentiel[] */
    public function getFichesReferentielsByMetier(Metier $metier): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('fichereferentiel.metier = :metier')->setParameter('metier', $metier);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** Facade ********************************************************************************************************/

    public function createWith(Referentiel $referentiel, Metier $metier): FicheReferentiel
    {
        $ficheReferentiel = new FicheReferentiel();
        $ficheReferentiel->setReferentiel($referentiel);
        $ficheReferentiel->setMetier($metier);
        $this->create($ficheReferentiel);
        return $ficheReferentiel;
    }
}