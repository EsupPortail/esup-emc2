<?php

namespace FicheMetier\Service\Activite;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\Activite;
use Laminas\Mvc\Controller\AbstractActionController;
use Metier\Entity\Db\Referentiel;
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
        $qb = $this->getObjectManager()->getRepository(Activite::class)->createQueryBuilder('activite');
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
            ->andWhere('activite.idOrig = :idOrig')->setParameter('idOrig', $idOrig)
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

    //todo fonction de recherche

    /** FACADE ********************************************************************************************************/

    public function readFromCsv(Referentiel $referentiel, string $file): array
    {
        $info = []; $warning = []; $error = []; $activites = []; $created = []; $updated = [];

        //lecture du header et dernimation
        $handle = fopen($file, "r");
        $header = fgetcsv($handle,null,";");
        // Remove BOM https://stackoverflow.com/questions/39026992/how-do-i-read-a-utf-csv-file-in-php-with-a-bom
        $header[0] = preg_replace(sprintf('/^%s/', pack('H*','EFBBBF')), "", $header[0]);

        $positionLibelle = array_search("libellé", $header);
        $positionDescription = array_search("description", $header);
        $positionId = array_search("identifiant", $header);

        $continue = true;
        if ($positionId === false) {
            $error[] = "La colonne obligatoire [identifiant] n'a pas été trouvée";
            $continue = false;
        }
        if ($positionLibelle === false) {
            $error[] = "La colonne obligatoire [libellé] n'a pas été trouvée";
            $continue = false;
        }
        if ($positionDescription === false) {
            $error[] = "La colonne facultative [description] n'a pas été trouvée";
            $continue = false;
        }

        if ($continue) {
            while ($content = fgetcsv($handle, null, ";")) {
                $identifiant = $content[$positionId];
                $libelle = $content[$positionLibelle];
                $description = ($positionDescription !== false) ? $content[$positionDescription] : null;
                $raw = json_encode($content);

                $activite = $this->getActiviteByReference($referentiel, $identifiant);
                if ($activite === null) {
                    $activite = new Activite();
                    $activite->setLibelle($libelle);
                    $activite->setDescription($description);
                    $activite->setIdOrig($identifiant);
                    $activite->setReferentiel($referentiel);
                    $activite->setRaw($raw);
                    $created[] = $activite;
                } else {
                    if ($activite->getRaw() !== $raw) {
                        $activite->setLibelle($libelle);
                        $activite->setDescription($description);
                        $activite->setIdOrig($identifiant);
                        $activite->setReferentiel($referentiel);
                        $activite->setRaw($raw);
                        $updated[] = $activite;
                    }
                }
                $activites[] = $activite;
            }
        }

        return ['activités' => $activites, 'created' => $created, 'updated' => $updated, 'info' => $info, 'warning' => $warning, 'error' => $error];


    }

}
