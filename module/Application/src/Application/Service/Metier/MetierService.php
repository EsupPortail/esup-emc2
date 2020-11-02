<?php

namespace Application\Service\Metier;

use Application\Entity\Db\Metier;
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
    public function create(Metier $metier)
    {
        $this->createFromTrait($metier);
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function update(Metier $metier)
    {
        $this->updateFromTrait($metier);
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function historise(Metier $metier)
    {
        $this->historiserFromTrait($metier);
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function restore(Metier $metier)
    {
        $this->restoreFromTrait($metier);
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function delete(Metier $metier)
    {
        $this->deleteFromTrait($metier);
        return $metier;
    }

    /** REQUETAGES ****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    private function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(Metier::class)->createQueryBuilder('metier')
            ->addSelect('domaine')->leftJoin('metier.domaines','domaine')
            ->addSelect('famille')->leftJoin('domaine.famille','famille')
            ->addSelect('fichemetier')->leftJoin('metier.fichesMetiers', 'fichemetier')
            ->addSelect('reference')->leftJoin('metier.references', 'reference')
        ;
        return $qb;
    }

    /**
     * @return Metier[]
     */
    public function getMetiers()
    {
        $qb = $this->createQueryBuilder()
            ->addOrderBy('metier.libelle')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Metier
     */
    public function getMetier(int $id)
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
     * @return Metier
     */
    public function getRequestedMetier(AbstractActionController $controller, $paramName = 'metier')
    {
        $id = $controller->params()->fromRoute($paramName);
        $metier = $this->getMetier($id);

        return $metier;
    }

    public function getMetiersTypesAsMultiOptions($historiser = false)
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

    public function generateCartographyArray()
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
                $fonction =  ($domaine) ? $domaine->getTypeFonction() : null;

                $entry = [
                    'metier'     => ($metier) ? $metier->__toString() : "---",
                    'niveau'     => ($metier) ? $metier->getNiveau() : "---",
                    'références' => implode("<br/>", $references),
                    'domaine'    => ($domaine) ? $domaine->__toString() : "---",
                    'fonction'   => ($fonction) ? $fonction : "---",
                    'famille'    => ($famille) ? $famille->__toString() : "---",
                    'nbFiche'    => count($metier->getFichesMetiers()),
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

}
