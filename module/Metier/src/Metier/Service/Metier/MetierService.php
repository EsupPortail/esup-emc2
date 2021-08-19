<?php

namespace Metier\Service\Metier;

use Metier\Entity\Db\Metier;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class MetierService {
    use GestionEntiteHistorisationTrait;

    /** GESTIONS DES ENTITES ******************************************************************************************/

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function create(Metier $metier) : Metier
    {
        $this->createFromTrait($metier);
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function update(Metier $metier) : Metier
    {
        $this->updateFromTrait($metier);
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function historise(Metier $metier) : Metier
    {
        $this->historiserFromTrait($metier);
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function restore(Metier $metier) : Metier
    {
        $this->restoreFromTrait($metier);
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function delete(Metier $metier) : Metier
    {
        $this->deleteFromTrait($metier);
        return $metier;
    }

    /** REQUETAGES ****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    private function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Metier::class)->createQueryBuilder('metier')
            ->addSelect('domaine')->leftJoin('metier.domaines','domaine')
            ->addSelect('famille')->leftJoin('domaine.famille','famille')
            ->addSelect('fichemetier')->leftJoin('metier.fichesMetiers', 'fichemetier')
            ->addSelect('reference')->leftJoin('metier.references', 'reference')
            ->addSelect('categorie')->leftJoin('metier.categorie', 'categorie')
            ->addSelect('niveaux')->leftJoin('metier.niveaux', 'niveaux')
//            ->addSelect('bas')->leftJoin('niveaux.borneInferieure', 'bas')
//            ->addSelect('haut')->leftJoin('niveaux.borneSuperieure', 'haut')
//            ->addSelect('rec')->leftJoin('niveaux.valeurRecommandee', 'rec')
        ;
        return $qb;
    }

    /**
     * @return Metier[]
     */
    public function getMetiers() : array
    {
        $qb = $this->createQueryBuilder()
            ->addOrderBy('metier.libelle')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Metier|null
     */
    public function getMetier(int $id) : ?Metier
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('metier.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Metier partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Metier|null
     */
    public function getRequestedMetier(AbstractActionController $controller, string $paramName = 'metier') : ?Metier
    {
        $id = $controller->params()->fromRoute($paramName);
        $metier = $this->getMetier($id);

        return $metier;
    }

    /**
     * @param bool $historiser
     * @return array
     */
    public function getMetiersTypesAsMultiOptions(bool $historiser = false) : array
    {
        /** @var Metier[] $metiers */
        $metiers = $this->getMetiers();

        $vide = [];
        $result = [];
        foreach ($metiers as $metier) {
            if ($historiser OR $metier->estNonHistorise())
                if ($metier->getDomaines()) {
                    foreach ($metier->getDomaines() as $domaine) {
                        $result[$domaine->getLibelle()][] = $metier;
                    }
                } else {
                    $vide[] = $metier;
                }
        }
        ksort($result);
        $multi = [];
        foreach ($result as $key => $metiers) {
            //['label'=>'A', 'options' => ["A" => "A", "a"=> "a"]],
            $options = [];
            foreach ($metiers as $metier) {
                    $options[$metier->getId()] = $metier->getLibelle();
            }
            $multi[] = ['label' => $key, 'options' => $options];
        }
        $options = [];
        foreach ($vide as $metier) {
            $options[$metier->getId()] = $metier->getLibelle();
        }
        $multi[] = ['label' => 'Sans domaine rattaché', 'options' => $options];
        return $multi;
    }

    /** FONCTIONS "METIERS" *******************************************************************************************/

    /**
     * @return array
     */
    public function generateCartographyArray() : array
    {
        $metiers = $this->getMetiers();

        $results = [];
        foreach($metiers as $metier) {
            $references = [];
            foreach ($metier->getReferences() as $reference) {
                $references[] = $reference->getTitre();
            }

            $domaines = $metier->getDomaines();
            if (empty($domaines)) $domaines[] = null;

            foreach ($domaines as $domaine) {
                $famille = ($domaine) ? $domaine->getFamille() : null;
                $fonction = ($domaine) ? $domaine->getTypeFonction() : null;

                $entry = [
                    'metier' => $metier->__toString(),
                    'niveau' => $metier->getNiveau(),
                    'références' => implode("<br/>", $references),
                    'domaine' => ($domaine) ? $domaine->__toString() : "---",
                    'fonction' => ($fonction) ?: "---",
                    'famille' => ($famille) ? $famille->__toString() : "---",
                    'nbFiche' => count($metier->getFichesMetiers()),
                ];
                $results[] = $entry;
            }
        }

        usort($results, function($a, $b) {
            if ($a['metier'] !== $b['metier'])  return $a['metier'] > $b['metier'];
            return $a['domaine'] > $b['domaine'];
        });

        return $results;
    }

    /** INCLUSIF TEST */

    /**
     * @param string $feminin
     * @param string $masculin
     * @return string|null
     */
    public static function computeEcritureInclusive(string $feminin, string $masculin) : ?string
    {
        $split_inclusif = [];
        $split_feminin = explode(" ",$feminin);
        $split_masculin = explode(" ",$masculin);

        if (count($split_feminin) !== count($split_masculin)) return null;
        $nbElement = count($split_feminin);

        for ($position = 0 ; $position < $nbElement; $position++) {
            if ($split_feminin[$position] !== "" AND strstr($split_feminin[$position], $split_masculin[$position]) === false) {
                $prefixe_commun = "";
                for ($i = 0 ; $i < min(strlen($split_feminin[$position]),strlen($split_masculin[$position])) ; $i++) {
                    if ($split_feminin[$position][$i] === $split_masculin[$position][$i]) {
                        $prefixe_commun .= $split_feminin[$position][$i];
                    } else {
                        do {
                            $prefixe_commun = substr($prefixe_commun, 0, strlen($prefixe_commun) - 1);
                            $suffixe_feminin = substr($split_feminin[$position], strlen($prefixe_commun));
                            $suffixe_masculin = substr($split_masculin[$position], strlen($prefixe_commun));
                        } while ( strlen($suffixe_masculin) <= 2);
                        $split_inclusif[] = $prefixe_commun . $suffixe_masculin . "·" . $suffixe_feminin;
                        break;
                    }
                }
            } else {
                $longueur = strlen($split_masculin[$position]);
                $suffixe = substr($split_feminin[$position], $longueur);
                if (strlen($suffixe) !== 0) {
                    $split_inclusif[] = $split_masculin[$position] . "·" . $suffixe;
                } else {
                    $split_inclusif[] = $split_feminin[$position];
                }
            }
        }

        return implode(" ", $split_inclusif);
    }
}
