<?php

namespace Formation\Service\Formation;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\Formateur;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationGroupe;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class FormationService
{
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function create(Formation $formation) : Formation
    {
        try {
            $this->getEntityManager()->persist($formation);
            $this->getEntityManager()->flush($formation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function update(Formation $formation) : Formation
    {
        try {
            $this->getEntityManager()->flush($formation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function historise(Formation $formation) : Formation
    {
        try {
            $formation->historiser();
            $this->getEntityManager()->flush($formation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function restore(Formation $formation) : Formation
    {
        try {
            $formation->dehistoriser();
            $this->getEntityManager()->flush($formation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function delete(Formation $formation) : Formation
    {
        try {
            $this->getEntityManager()->remove($formation);
            $this->getEntityManager()->flush($formation);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $formation;
    }

    /** REQUETAGES ****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
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
    public function getFormations(string $champ = 'libelle', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('groupe.libelle, formation.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param FormationGroupe|null $groupe
     * @param string $champ
     * @param string $ordre
     * @return Formation[]
     */
    public function getFormationsByGroupe(?FormationGroupe $groupe, string $champ = 'libelle', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('groupe.libelle, formation.' . $champ, $ordre);

        if ($groupe !== null) {
            $qb = $qb->andWhere('formation.groupe = :groupe')
                     ->setParameter('groupe', $groupe);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int|null $id
     * @return Formation|null
     */
    public function getFormation(?int $id) : ?Formation
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
     * @return Formation|null
     */
    public function getRequestedFormation(AbstractActionController $controller, string $paramName = 'formation') : ?Formation
    {
        $id = $controller->params()->fromRoute($paramName);
        $activite = $this->getFormation($id);
        return $activite;
    }

    public function getFormationBySource(string $source, string $id) : ?Formation
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
    public function getFormationsAsOptions() : array
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
    public function getFormationsDisponiblesAsOptions(array $formationsAlreadyUsed = []) : array
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
    public function getFormationsGroupesAsGroupOptions() : array
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
    public function updateLibelle(Formation $formation, array $data) : Formation
    {
        /** @var string $libelle */
        $libelle = null;
        if (isset($data['libelle'])) $libelle = $data['libelle'];
        $formation->setLibelle($libelle);
        $this->update($formation);
        return $formation;
    }

    /** RECHERCHES ****************************************************************************************************/

    /**
     * @param string $texte
     * @return Formation[]
     */
    public function findFormationByTerm(string $texte) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("LOWER(CONCAT(formation.libelle, ' ', groupe.libelle)) like :search OR LOWER(CONCAT(groupe.libelle, ' ', formation.libelle)) like :search")
            ->setParameter('search', '%'.strtolower($texte).'%');
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param Formation[] $formations
     * @return array
     */
    public function formatFormationtJSON(array $formations) : array
    {
        $result = [];
        /** @var Formation[] $formations */
        foreach ($formations as $formation) {
            $groupe = $formation->getGroupe();
            $result[] = array(
                'id' => $formation->getId(),
                'label' => (($groupe !== null)?$groupe->getLibelle():"Sans thème ") . " > " . $formation->getLibelle(),
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }

    /**
     * @param string $texte
     * @return array
     */
    public function findFormateurByTerm(string $texte) : array
    {
        $qb = $this->getEntityManager()->getRepository(Formateur::class)->createQueryBuilder('formateur')
            ->andWhere("LOWER(CONCAT(formateur.prenom, ' ', formateur.nom)) like :search OR LOWER(CONCAT(formateur.nom, ' ', formateur.prenom)) like :search")
            ->setParameter('search', '%'.strtolower($texte).'%');
        $result = $qb->getQuery()->getResult();

        $data = [];
        /** @var Formateur $f */
        foreach ($result as $f) {
            $data[$f->getEmail()] = $f->getPrenom(). ' ' . strtoupper($f->getNom());
        }

        return $data;
    }

    /**
     * @param array $formateurs [ $email => $denomination ]
     * @return array
     */
    public function formatFormateurJSON(array $formateurs) : array
    {
        $result = [];
        /** @var Formateur[] $formateurs */
        foreach ($formateurs as $email => $denomination) {
            $result[] = array(
                'id' => $email,
                'label' => $denomination,
                'extra' => "<span class='badge'>".$email."</span>",
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }

}
