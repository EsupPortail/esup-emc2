<?php

namespace Formation\Service\Formation;

use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\Axe;
use Formation\Entity\Db\Domaine;
use Formation\Entity\Db\Formateur;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationGroupe;
use Formation\Provider\Role\FormationRoles;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class FormationService
{
    use ProvidesObjectManager;
    use UserServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function create(Formation $formation): Formation
    {
        $this->getObjectManager()->persist($formation);
        $this->getObjectManager()->flush($formation);
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function update(Formation $formation): Formation
    {
        $this->getObjectManager()->flush($formation);
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function historise(Formation $formation): Formation
    {
        $formation->historiser();
        $this->getObjectManager()->flush($formation);
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function restore(Formation $formation): Formation
    {
        $formation->dehistoriser();
        $this->getObjectManager()->flush($formation);
        return $formation;
    }

    /**
     * @param Formation $formation
     * @return Formation
     */
    public function delete(Formation $formation): Formation
    {
        $this->getObjectManager()->remove($formation);
        $this->getObjectManager()->flush($formation);
        return $formation;
    }

    /** REQUETAGES ****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Formation::class)->createQueryBuilder('formation')
            ->addSelect('groupe')->leftJoin('formation.groupe', 'groupe')
            ->addSelect('axe')->leftJoin('groupe.axe', 'axe')
            /** ceci semble provoquer un déborderment mémoire en demo **/
//            ->addSelect('competence')->leftJoin('formation.competences', 'competence')
//            ->addSelect('niveau_c')->leftJoin('competence.niveau', 'niveau_c')
//            ->addSelect('application')->leftJoin('formation.applications', 'application')
//            ->addSelect('niveau_a')->leftJoin('application.niveau', 'niveau_a')
        ;
        return $qb;
    }

    /** @return Formation[] */
    public function getFormations(string $champ = 'libelle', string $ordre = 'ASC', bool $histo=false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('groupe.libelle, formation.' . $champ, $ordre);
        if (!$histo) $qb = $qb->andWhere('formation.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param FormationGroupe|null $groupe
     * @param string $champ
     * @param string $ordre
     * @return Formation[]
     */
    public function getFormationsByGroupe(?FormationGroupe $groupe, string $champ = 'libelle', string $ordre = 'ASC'): array
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
    public function getFormation(?int $id): ?Formation
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
    public function getRequestedFormation(AbstractActionController $controller, string $paramName = 'formation'): ?Formation
    {
        $id = $controller->params()->fromRoute($paramName);
        $activite = $this->getFormation($id);
        return $activite;
    }

    public function getFormationBySource(string $source, string $id): ?Formation
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formation.source = :source')
            ->andWhere('formation.idSource = :id')
            ->setParameter('source', $source)
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Formation partagent la même source [" . $source . "-" . $id . "]",0,$e);
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getFormationsAsOptions(): array
    {
        $formations = $this->getFormations();

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
    public function getFormationsDisponiblesAsOptions(array $formationsAlreadyUsed = []): array
    {
        $formations = $this->getFormations();

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
    public function getFormationsGroupesAsGroupOptions(): array
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
                return strcmp($a->getLibelle(),$b->getLibelle());
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

    public function getFormationByLibelle(?string $libelle, ?FormationGroupe $theme = null, ?Axe $axe = null): ?Formation
    {
        if ($libelle === null) return null;

        $qb = $this->createQueryBuilder()
            ->andWhere('formation.libelle = :libelle') ->setParameter('libelle', $libelle)
        ;
        if ($theme) $qb = $qb->andWhere('formation.groupe = :groupe')->setParameter('groupe', $theme);
        if ($axe) $qb = $qb->andWhere('groupe.axe = :axe')->setParameter('axe', $axe);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Formation::class."] partagent le même libellé [".$libelle."].",0, $e);
        }
        return $result;
    }

    /** RECHERCHES ****************************************************************************************************/

    /**
     * @param string $texte
     * @return Formation[]
     */
    public function findFormationByTerm(string $texte): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("LOWER(CONCAT(formation.libelle, ' ', groupe.libelle)) like :search OR LOWER(CONCAT(groupe.libelle, ' ', formation.libelle)) like :search")
            ->setParameter('search', '%' . strtolower($texte) . '%');
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param string $texte
     * @return Formation[]
     */
    public function findFormationsActivesByTerm(string $texte): array
    {
        $qb = $this->getObjectManager()->getRepository(Formation::class)->createQueryBuilder('formation')
            ->join('formation.plans', 'plan')->addSelect('plan')
            ->andWhere('plan.dateDebut <= :now AND plan.dateFin >= :now')->setParameter('now', new DateTime())
            ->andWhere("LOWER(formation.libelle) like :search")->setParameter('search', '%' . strtolower($texte) . '%')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Formation[] $formations
     * @return array
     */
    public function formatFormationsJSON(array $formations): array
    {
        $result = [];
        foreach ($formations as $formation) {
            $domaines = $formation->getDomaines();
            $extra = "";
            foreach ($domaines as $domaine) { $extra .= " <span class='badge' style='background:".$domaine->getCouleur()."'>".$domaine->getLibelle()."</span>"; }
            $result[] = array(
                'id' => $formation->getId(),
                'label' => $formation->getLibelle(),
                'extra' => $extra,
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
    public function findFormateurByTerm(string $texte): array
    {
        $qb = $this->getObjectManager()->getRepository(Formateur::class)->createQueryBuilder('formateur')
            ->andWhere("LOWER(CONCAT(formateur.prenom, ' ', formateur.nom)) like :search OR LOWER(CONCAT(formateur.nom, ' ', formateur.prenom)) like :search")
            ->setParameter('search', '%' . strtolower($texte) . '%');
        $result = $qb->getQuery()->getResult();

        $data = [];
        /** @var Formateur $f */
        foreach ($result as $f) {
            $data[$f->getEmail()] = $f->getPrenom() . ' ' . strtoupper($f->getNom());
        }

        return $data;
    }

    /**
     * @param array $formateurs [ $email => $denomination ]
     * @return array
     */
    public function formatFormateurJSON(array $formateurs): array
    {
        $result = [];
        /** @var Formateur[] $formateurs */
        foreach ($formateurs as $email => $denomination) {
            $result[] = array(
                'id' => $email,
                'label' => $denomination,
                'extra' => "<span class='badge'>" . $email . "</span>",
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }

    /** FACADE *************************************************************************************/

    public function getGestionnaires(): array
    {
        $gestionnaires = $this->getUserService()->getUtilisateursByRoleIdAsOptions(FormationRoles::GESTIONNAIRE_FORMATION);
        return $gestionnaires;
    }

    public function createFormation(string $libelle, ?FormationGroupe $theme): Formation
    {
        $formation = new Formation();
        $formation->setLibelle($libelle);
        $formation->setGroupe($theme);
        $this->create($formation);
        return $formation;
    }

    public function genererDictionnaireParDomaine(array $plansDeFormation) : array
    {
        $actions  = [];
        $domaines = [];
        $actionsByDomaines = [];

        $sansDomaine = new Domaine();
        $sansDomaine->setLibelle("Sans domaine");
        $sansDomaine->setOrdre(999999);

        foreach ($plansDeFormation as $planDeFormation) {
            foreach ($planDeFormation->getFormations() as $action) {
                $actions[$action->getId()] = $action;
                $domaines_ = $action->getDomaines();
                if (empty($domaines_)) {
                    $domaines[-1] = $sansDomaine;
                    $actionsByDomaines[-1][] = $action;
                } else {
                    foreach ($domaines_ as $domaine) {
                        $domaines[$domaine->getId()] = $domaine;
                        $actionsByDomaines[$domaine->getId()][] = $action;
                    }
                }
            }
        }

        return [$actions, $domaines, $actionsByDomaines];
    }

}
