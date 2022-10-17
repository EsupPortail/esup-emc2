<?php

namespace Metier\Service\Metier;

use Carriere\Service\Niveau\NiveauService;
use Doctrine\DBAL\Driver\Exception as DRV_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use Doctrine\ORM\ORMException;
use Metier\Entity\Db\Metier;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class MetierService {
    use EntityManagerAwareTrait;

    /** GESTIONS DES ENTITES ******************************************************************************************/

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function create(Metier $metier) : Metier
    {
        try {
            $this->getEntityManager()->persist($metier);
            $this->getEntityManager()->flush($metier);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function update(Metier $metier) : Metier
    {
        try {
            $this->getEntityManager()->flush($metier);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function historise(Metier $metier) : Metier
    {
        try {
            $metier->historiser();
            $this->getEntityManager()->flush($metier);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function restore(Metier $metier) : Metier
    {
        try {
            $metier->dehistoriser();
            $this->getEntityManager()->flush($metier);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function delete(Metier $metier) : Metier
    {
        try {
            $this->getEntityManager()->remove($metier);
            $this->getEntityManager()->flush($metier);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
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
        ;
        $qb = NiveauService::decorateWithNiveau($qb, 'metier', 'niveaux');
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

                    'niveau' => ($metier->getNiveaux())?"[".$metier->getNiveaux()->getBorneInferieure()->getEtiquette().":".$metier->getNiveaux()->getBorneSuperieure()->getEtiquette()."]":"---",
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
        $split_feminin_ = explode(" ",$feminin);
        $split_feminin = []; foreach ($split_feminin_ as $item) { if ($item !== '') $split_feminin[] = $item; }
//        var_dump($split_feminin);
        $split_masculin_ = explode(" ",$masculin);
        $split_masculin = []; foreach ($split_masculin_ as $item) { if ($item !== '') $split_masculin[] = $item; }
//        var_dump($split_masculin);

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

    /**
     * @param Metier $metier
     * @return array
     */
    public function getInfosAgentsByMetier(Metier $metier) : array
    {
        $params = ["metier" => $metier->getId()];
        $sql = <<<EOS
select m.id, m.libelle_default,
       f.id, fp.id, fte.principale, fte.quotite,
       a.c_individu, a.prenom, a.nom_usage, 
       g.id as g_id, g.lib_court, ag.d_debut as g_debut, ag.d_fin as g_fin,
       cp.categorie as categorie,
       s.id as s_id, s.libelle_court, aa.date_debut as s_debut, aa.date_fin as s_fin
from metier_metier m
left join metier_metier_domaine md on m.id = md.metier_id
left join metier_domaine d on md.domaine_id = d.id
left join fichemetier f on m.id = f.metier_id
left join ficheposte_fichemetier fte on f.id = fte.fiche_type
left join ficheposte fp on fte.fiche_poste = fp.id
left join agent a on fp.agent = a.c_individu
left join agent_carriere_grade ag on a.c_individu=ag.agent_id
left join carriere_grade g on ag.grade_id = g.id
left join carriere_corps cp on ag.corps_id = cp.id
left join agent_carriere_affectation aa on a.c_individu = aa.agent_id
left join structure s on aa.structure_id = s.id
where m.id = :metier
and ag.deleted_on IS NULL
and ag.d_debut < current_date and (ag.d_fin IS NULL OR ag.d_fin > current_date)
and aa.deleted_on IS NULL
and aa.date_debut < current_date and (aa.date_fin IS NULL OR aa.date_fin > current_date)
order by a.nom_usage, a.prenom
EOS;

            $tmp = null;
            try {
                $res = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
                try {
                    $tmp = $res->fetchAllAssociative();
                } catch (DRV_Exception $e) {
                    throw new RuntimeException("Un problème est survenue lors de la récupération des fonctions d'un groupe d'individus", 0, $e);
                }
            } catch (DBA_Exception $e) {
                throw new RuntimeException("Un problème est survenue lors de la récupération des fonctions d'un groupe d'individus", 0, $e);
            }
            return $tmp;
    }
}
