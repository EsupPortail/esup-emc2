<?php

namespace Formation\Service\Formation;

use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\Formation;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class FormationService
{
    use GestionEntiteHistorisationTrait;

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
            ->addSelect('groupe')->leftJoin('formation.groupe', 'groupe')
            ->addSelect('competence')->leftJoin('formation.competences', 'competence')
            ->addSelect('niveau_c')->leftJoin('competence.niveau', 'niveau_c')
            ->addSelect('application')->leftJoin('formation.applications', 'application')
            ->addSelect('niveau_a')->leftJoin('application.niveau', 'niveau_a')
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
            ->orderBy('groupe.libelle, formation.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int|null $id
     * @return Formation
     */
    public function getFormation(?int $id)
    {
        if ($id === null) return null;
        $qb = $this->createQueryBuilder()
            ->andWhere('formation.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs formations portent le même identifiant [' . $id . ']', $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Formation
     */
    public function getRequestedFormation(AbstractActionController $controller, $paramName = 'formation')
    {
        $id = $controller->params()->fromRoute($paramName);
        $activite = $this->getFormation($id);
        return $activite;
    }

    /**
     * @param string $source
     * @param string $id
     * @return Formation|null
     */
    public function getFormationBySource(string $source, string $id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formation.source = :source')
            ->andWhere('formation.idSource = :id')
            ->setParameter('source', $source)
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Formation partagent la même source [".$source. "-". $id ."]");
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getFormationsAsOptions()
    {
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
            if (!$found) $result[] = $formation;
        }

        return Formation::generateOptions($result);
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
            usort($listing, function (Formation $a, Formation $b) {
                return $a->getLibelle() > $b->getLibelle();
            });

            foreach ($listing as $formation) {
                $optionsoptions[$formation->getId()] = $formation->getLibelle();
            }

            $options[] = [
                'label' => ($clef === "ZZZ") ? "Sans groupe" : $clef,
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
    public function updateLibelle(Formation $formation, array $data)
    {
        /** @var string $libelle */
        $libelle = null;
        if (isset($data['libelle'])) $libelle = $data['libelle'];
        $formation->setLibelle($libelle);
        $this->update($formation);
        return $formation;
    }
}
