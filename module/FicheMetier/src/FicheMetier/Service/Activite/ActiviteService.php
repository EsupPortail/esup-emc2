<?php

namespace FicheMetier\Service\Activite;

use Doctrine\DBAL\Driver\Exception as DRV_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\Activite;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use Laminas\Mvc\Controller\AbstractActionController;
use Referentiel\Entity\Db\Referentiel;
use RuntimeException;

class ActiviteService
{
    use ProvidesObjectManager;

    /** ENTITE ********************************************************************************************************/

    public function create(Activite $activite): void
    {
        $this->getObjectManager()->persist($activite);
        $this->getObjectManager()->flush($activite);
    }

    public function update(Activite $activite): void
    {
        $this->getObjectManager()->flush($activite);
    }

    public function historise(Activite $activite): void
    {
        $activite->historiser();
        $this->getObjectManager()->flush($activite);
    }

    public function restore(Activite $activite): void
    {
        $activite->dehistoriser();
        $this->getObjectManager()->flush($activite);
    }

    public function delete(Activite $activite): void
    {
        $this->getObjectManager()->remove($activite);
        $this->getObjectManager()->flush($activite);
    }

    /** QUERY *********************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Activite::class)->createQueryBuilder('activite')
            ->leftJoin('activite.referentiel', 'referentiel')->addSelect('referentiel')
        ;
        return $qb;
    }

    public function getActivite(?int $id = null): ?Activite
    {
        $qb = $this->createQueryBuilder()
            ->where('activite.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Activite::class."] partagent le même id",-1,$e);
        }
        return $result;
    }

    public function getRequestedActivite(AbstractActionController $controller, string $param="activite"): ?Activite
    {
        $id = $controller->params()->fromRoute($param);
        $activite = $this->getActivite($id);
        return $activite;
    }

    public function getActiviteByReference(Referentiel $referentiel, string $idOrig): ?Activite
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('activite.referentiel = :referentiel')->setParameter('referentiel', $referentiel)
            ->andWhere('activite.reference = :idOrig')->setParameter('idOrig', $idOrig)
        ;
        try {
            $activite = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Activite::class."] partagent la même identification [".$idOrig."] dans le même référentiel [".$referentiel->getLibelleCourt()."]", -1 ,$e);
        }
        return $activite;
    }

    /** @return Activite[] */
    public function getActivites(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder();
        if (!$withHisto) { $qb = $qb->andWhere('activite.histoDestruction IS NULL'); }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Activite[] */
    public function getActivitesWithFiltre(array $params = []): array
    {
        $qb = $this->createQueryBuilder();
        if (isset($params['referentiel']) and $params['referentiel'] !== '') {
            $referentielId = $params['referentiel'];
            $qb = $qb->andWhere('referentiel.id = :referentiel')->setParameter('referentiel', $referentielId);
        }
        if (isset($params['histo']) and $params['histo'] !== '') {
            if ($params['histo'] == 0) $qb = $qb->andWhere('activite.histoDestruction IS NULL');
            if ($params['histo'] == 1) $qb = $qb->andWhere('activite.histoDestruction IS NOT NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getActivitesAsOptions(): array
    {
        $activites = $this->getActivites();

        $options = [];
        foreach ($activites as $activite) {
            $options[$activite->getId()] = $this->optionify($activite);
        }
        return $options;
    }

    /** @return Activite[] */
    public function getActivitesByTerm(string $term): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('LOWER(activite.libelle) like :search')
            ->setParameter('search', '%' . strtolower($term) . '%');
        $result = $qb->getQuery()->getResult();

        $activites = [];
        /** @var Activite $item */
        foreach ($result as $item) {
            $activites[$item->getId()] = $item;
        }
        return $activites;
    }

    public function getActiviteByLibelle(?string $libelle): ?Activite
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('activite.libelle = :libelle')->setParameter('libelle', $libelle);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            // todo probablement ajouter la notion de référentiel ...
            throw new RuntimeException("Plusieurs [" . Activite::class . "] partagent le même libellé [" . $libelle . "]", -1, $e);
        }
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function createWith(string $intitule, Referentiel $referentiel, string $reference, bool $perist = true): ?Activite
    {
        $activite = new Activite();
        $activite->setLibelle($intitule);
        $activite->setReferentiel($referentiel);
        $activite->setReference($reference);
        if ($perist) $this->create($activite);

        return $activite;
    }

    public function generateDictionnaireFicheMetier(): array
    {
        $sql = <<<EOS
select ae.activite_id, count (DISTINCT fma.fichemetier_id) as count
from fichemetier_activite fma
join activite_element ae on ae.id = fma.activite_element_id
join fichemetier fm on fm.id = fma.fichemetier_id
where ae.histo_destruction IS NULL and fm.histo_destruction IS NULL
group by ae.activite_id
EOS;

        try {
            $params = [];
            $res = $this->getObjectManager()->getConnection()->executeQuery($sql, $params);
            try {
                $tmp = $res->fetchAllAssociative();
            } catch (DRV_Exception $e) {
                throw new RuntimeException("[DRV] Un problème est survenu lors du calcul du dictionnaire", 0, $e);
            }
        } catch (DBA_Exception $e) {
            throw new RuntimeException("[DBA] Un problème est survenu lors du calcul du dictionnaire", 0, $e);
        }

        $dictionnaire = [];
        foreach ($tmp as $item) {
            $dictionnaire[$item['activite_id']] = $item['count'];
        }
        return $dictionnaire;
    }

    private function optionify(Activite $activite): array
    {
//        $texte  = $mission->getLibelle();
        $texte = "<span class='libelle_activite shorten'>" . MissionPrincipaleService::tronquerTexte($activite->getLibelle(), 100) . "</span>";
        $texte .= "<span class='libelle_activite full' style='display: none'>" . $activite->getLibelle() . "</span>";
//        $description = $activite->getDescription();
//        $texte = "<span class='activite' title='" . ($description ?? "Aucune description") . "' class='badge btn-danger'>" . $texte;

        if ($activite->getCodesFicheMetier() !== null) {
            $texte .= "&nbsp;" . "<span class='badge'>" .
                $activite->getCodesFicheMetier()
                . "</span>";
        }

        $texte .= "<span class='description' style='display: none' onmouseenter='alert(event.target);'>" . ($description ?? "Aucune description") . "</span>"
            . "</span>";

        $this_option = [
            'value' => $activite->getId(),
            'attributes' => [
                'data-content' => $texte
            ],
            'label' => $texte,
        ];
        return $this_option;
    }
}
