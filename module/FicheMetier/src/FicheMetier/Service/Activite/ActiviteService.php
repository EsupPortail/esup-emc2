<?php

namespace FicheMetier\Service\Activite;

use Doctrine\DBAL\Driver\Exception as DRV_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\Activite;
use FicheMetier\Entity\Db\FicheMetier;
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
            if ($params['histo'] == 0) $qb = $qb->andWhere('mission.histoDestruction IS NULL');
            if ($params['histo'] == 1) $qb = $qb->andWhere('mission.histoDestruction IS NOT NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    //todo fonction de recherche

    /** FACADE ********************************************************************************************************/

    public function createOneWithCsv(array $json, string $separateur, Referentiel $referentiel, ?int $position): Activite
    {

        /* CHAMPS OBLIGATOIRE *****************************************************************************************/

        if (!isset($json[Activite::ACTIVITE_HEADER_ID]) or trim($json[Activite::ACTIVITE_HEADER_ID]) === '') {
            throw new RuntimeException("La colonne obligatoire [" . Activite::ACTIVITE_HEADER_ID . "] est manquante dans le fichier CSV sur la ligne [" . ($position ?? "non préciser") . "]");
        } else $idOrig = trim($json[Activite::ACTIVITE_HEADER_ID]);

        if (!isset($json[Activite::ACTIVITE_HEADER_LIBELLE]) or trim($json[Activite::ACTIVITE_HEADER_LIBELLE]) === '') {
            throw new RuntimeException("La colonne obligatoire [" . Activite::ACTIVITE_HEADER_LIBELLE . "] est manquante dans le fichier CSV sur la ligne [" . ($position ?? "non préciser") . "]");
        } else $libelle = trim($json[Activite::ACTIVITE_HEADER_LIBELLE]);

        /** RECUPERATION OR CREATION **********************************************************************************/

        $activite = $this->getActiviteByReference($referentiel, $idOrig);
        if ($activite === null) {
            $activite = new Activite();
            $activite->setReferentiel($referentiel);
            $activite->setReference($idOrig);
            $activite->setLibelle($libelle);
        }

        /** RECUPERATION DES AUTRES DONNEES ***************************************************************************/

        if (isset($json[Activite::ACTIVITE_HEADER_DESCRIPTION]) and trim($json[Activite::ACTIVITE_HEADER_DESCRIPTION]) != '') {
            $description = trim($json[Activite::ACTIVITE_HEADER_DESCRIPTION]);
            $activite->setDescription($description);
        }
        if (isset($json[Activite::ACTIVITE_HEADER_CODES_EMPLOITYPE])) {
            if (trim($json[Activite::ACTIVITE_HEADER_CODES_EMPLOITYPE]) !== '') {
                $codesFicheMetier = trim($json[Activite::ACTIVITE_HEADER_CODES_EMPLOITYPE]);
                $activite->setCodesFicheMetier($codesFicheMetier);
            } else $activite->setCodesFicheMetier(null);
        }
        if (isset($json[Activite::ACTIVITE_HEADER_CODES_FONCTION])) {
            if (trim($json[Activite::ACTIVITE_HEADER_CODES_FONCTION]) !== '') {
                $codesCodeFonction = trim($json[Activite::ACTIVITE_HEADER_CODES_FONCTION]);
                $activite->setCodesFonction($codesCodeFonction);
            } else $activite->setCodesFonction(null);
        }

        $activite->setRaw(json_encode($json));

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
}
