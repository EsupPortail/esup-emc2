<?php

namespace Application\Service\Formation;

use Application\Entity\Db\Formation;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class FormationService {
//    use EntityManagerAwareTrait;
//    use UserServiceAwareTrait;
//    use DateTimeAwareTrait;
    use GestionEntiteHistorisationTrait;
    use FormationThemeServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function create(Formation $formation)
    {
        $this->createFromTrait($formation);
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function update(Formation $formation)
    {
        $this->updateFromTrait($formation);
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function historise(Formation $formation)
    {
        $this->historiserFromTrait($formation);
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function restore(Formation $formation)
    {
        $this->restoreFromTrait($formation);
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function delete(Formation $formation)
    {
        $this->deleteFromTrait($formation);
        return $formation;
    }

    /** REQUETAGES ****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(Formation::class)->createQueryBuilder('formation')
            ->addSelect('createur')->join('formation.histoCreateur', 'createur')
            ->addSelect('modificateur')->join('formation.histoModificateur', 'modificateur')
            ->addSelect('destructeur')->leftjoin('formation.histoDestructeur', 'destructeur')
            ->addSelect('theme')->leftJoin('formation.theme', 'theme')
            ->addSelect('groupe')->leftJoin('formation.groupe', 'groupe')
            ->addSelect('finstance')->leftJoin('formation.instances', 'finstance')
            ->addSelect('journee')->leftJoin('finstance.journees', 'journee')
//            ->addSelect('inscrit')->leftJoin('finstance.inscrits', 'inscrit')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Formation[]
     */
    public function getFormations($champ = 'libelle', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('formation.' . $champ, $ordre)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $id
     * @return Formation
     */
    public function getFormation($id)
    {
        if ($id === null) return null;
        $qb = $this->createQueryBuilder()
            ->andWhere('formation.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs formations portent le même identifiant ['.$id.']', $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Formation
     */
    public function getRequestedFormation($controller, $paramName = 'formation')
    {
        $id = $controller->params()->fromRoute($paramName);
        $activite = $this->getFormation($id);
        return $activite;
    }

    /**
     * @return array
     */
    public function getFormationsAsOptions() {
        $formations = $this->getFormations('libelle');

        $result = [];
        foreach ($formations as $formation) {
            $result[$formation->getId()] = $formation->getLibelle();
        }
        return $result;
    }

    /**
     * @param Formation[] $formationsAlreadyUsed
     * @return Formation[]
     */
    public function getFormationsDisponiblesAsOptions($formationsAlreadyUsed = [])
    {
        $formations = $this->getFormations('libelle', 'ASC');

        $result = [];
        foreach ($formations as $formation) {
            $found = false;
            if ($formationsAlreadyUsed !== null) {
                foreach ($formationsAlreadyUsed as $used) {
                    if ($used->getId() === $formation->getId()) {
                        $found = true;
                        break;
                    }
                }
            }
            if (! $found ) $result[] = $formation;
        }

        return Formation::generateOptions($result);
    }

    /** FORMATION THEME ***********************************************************************************************/

    /**
     * @return array
     */
    public function getFormationsThemesAsGroupOptions()
    {
        $formations = $this->getFormations();
        $dictionnaire = [];
        foreach ($formations as $formation) {
            $libelle = ($formation->getTheme()) ? $formation->getTheme()->getLibelle() : "Sans Thèmes";
            $dictionnaire[$libelle][] = $formation;
        }
        ksort($dictionnaire);

        $options = [];
        foreach ($dictionnaire as $clef => $listing) {
            $optionsoptions = [];
            usort($listing, function (Formation $a, Formation $b) { return $a->getLibelle() > $b->getLibelle();});

            foreach ($listing as $formation) {
                $optionsoptions[$formation->getId()] = $formation->getLibelle();
            }

            $options[] = [
                'label' => $clef,
                'options' => $optionsoptions,
            ];
        }
        return $options;
    }

    /**
     * @return array
     */
    public function getFormationsGroupesAsGroupOptions()
    {
        $formations = $this->getFormations();
        $dictionnaire = [];
        foreach ($formations as $formation) {
            $libelle = ($formation->getGroupe()) ? $formation->getGroupe()->getLibelle() : "ZZZ";
            $dictionnaire[$libelle][] = $formation;
        }
        ksort($dictionnaire);

        $options = [];
        foreach ($dictionnaire as $clef => $listing) {
            $optionsoptions = [];
            usort($listing, function (Formation $a, Formation $b) { return $a->getLibelle() > $b->getLibelle();});

            foreach ($listing as $formation) {
                $optionsoptions[$formation->getId()] = $formation->getLibelle();
            }

            $options[] = [
                'label' => ($clef === "ZZZ")?"Sans groupe":$clef,
                'options' => $optionsoptions,
            ];
        }

        return $options;
    }

    /**
     * @param Formation $formation
     * @param array $data
     * @return Formation
     */
    public function updateLibelle(Formation $formation, $data)
    {
        /** @var string $libelle */
        $libelle = null;
        if (isset($data['libelle'])) $libelle = $data['libelle'];
        $formation->setLibelle($libelle);
        $this->update($formation);
        return $formation;
    }

}
