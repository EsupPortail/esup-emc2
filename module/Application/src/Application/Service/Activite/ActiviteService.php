<?php

namespace Application\Service\Activite;

use Application\Entity\Db\Activite;
use Application\Entity\Db\ActiviteLibelle;
use Application\Entity\Db\Competence;
use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\FicheMetierTypeActivite;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class ActiviteService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITÉS *******************************************************************************************/

    /**
     * @param Activite $activite
     * @return Activite
     */
    public function create(Activite $activite) : Activite
    {
        $this->createFromTrait($activite);
        return $activite;
    }

    /**
     * @param Activite $activite
     * @return Activite
     */
    public function update(Activite $activite) : Activite
    {
        $this->updateFromTrait($activite);
        return $activite;
    }

    /**
     * @param Activite $activite
     * @return Activite
     */
    public function historise(Activite $activite) : Activite
    {
        $this->historiserFromTrait($activite);
        return $activite;
    }

    /**
     * @param Activite $activite
     * @return Activite
     */
    public function restore(Activite $activite) : Activite
    {
        $this->restoreFromTrait($activite);
        return $activite;
    }

    /**
     * @param Activite $activite
     * @return Activite
     */
    public function delete(Activite $activite) : Activite
    {
        $this->deleteFromTrait($activite);
        return $activite;
    }

    /** REQUETES ******************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Activite::class)->createQueryBuilder('activite')
            ->addSelect('createur')->join('activite.histoCreateur', 'createur')
            ->addSelect('modificateur')->join('activite.histoModificateur', 'modificateur')
            ->addSelect('destructeur')->leftJoin('activite.histoDestructeur', 'destructeur')
            ->addSelect('libelle')->leftJoin('activite.libelles', 'libelle')
            ->addSelect('description')->leftJoin('activite.descriptions', 'description')
            ->addSelect('applicationelement')->leftJoin('activite.applications', 'applicationelement')
            ->addSelect('application')->leftJoin('applicationelement.application', 'application')
            ->addSelect('competenceelement')->leftJoin('activite.competences', 'competenceelement')
            ->addSelect('competence')->leftJoin('competenceelement.competence', 'competence')
            ->addSelect('formationelement')->leftJoin('activite.formations', 'formationelement')
            ->addSelect('formation')->leftJoin('formationelement.formation', 'formation')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Activite[]
     */
    public function getActivites(string $champ = 'id', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->addOrderBy('activite.' . $champ, $ordre)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param FicheMetier|null $ficheMetier
     * @return array
     */
    public function getActivitesAsOptions(FicheMetier $ficheMetier = null) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('activite.histoDestruction IS NULL')
        ;
        $result = $qb->getQuery()->getResult();

        $activites = [];
        if ($ficheMetier) {
            $activites = [];
            foreach ($ficheMetier->getActivites() as $activite) {
                $activites[] = $activite->getActivite();
            }
        }

        $options = [];
        /** @var Activite $item */
        foreach ($result as $item) {
            $res = array_filter($activites, function (Activite $a) use ($item) {return $a->getId() === $item->getId();});
            if (empty($res)) $options[$item->getId()] = $item->getLibelle();
        }
        return $options;
    }
    /**
     * @param int|null $id
     * @return Activite|null
     */
    public function getActivite(?int $id) : ?Activite
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('activite.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs activités portent le même identifiant ['.$id.']', $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Activite|null
     */
    public function getRequestedActivite(AbstractActionController $controller, string $paramName = 'activite') : ?Activite
    {
        $id = $controller->params()->fromRoute($paramName);
        $activite = $this->getActivite($id);
        return $activite;
    }

    /**
     * @param int $id
     * @return FicheMetierTypeActivite|null
     */
    public function getFicheMetierTypeActivite(int $id) : ?FicheMetierTypeActivite
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetierTypeActivite::class)->createQueryBuilder('activite')
            ->andWhere('activite.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs couples (FicheMetierType,Activite) portent le même identifiant ['.$id.']', $e);
        }
        return $result;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetierTypeActivite[]
     */
    public function getActivitesByFicheMetierType(FicheMetier $fiche) : array
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetierTypeActivite::class)->createQueryBuilder('couple')
            ->addSelect('fiche')
            ->join('couple.fiche', 'fiche')
            ->addSelect('activite')
            ->join('couple.activite', 'activite')
            ->andWhere('fiche.id = :id')
            ->setParameter('id', $fiche->getId())
            ->orderBy('couple.position')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param FicheMetierTypeActivite $couple
     */
    public function moveUp(FicheMetierTypeActivite $couple) {
        $currentPosition = $couple->getPosition();
        if ($currentPosition !== 1) {
            $activites = $this->getActivitesByFicheMetierType($couple->getFiche());

            $swapWith = null;
            foreach ($activites as $activite) {
                if ($activite->getPosition() === $currentPosition - 1) {
                    $swapWith = $activite;
                    break;
                }
            }

            if ($swapWith) {
                $swapWith->setPosition($currentPosition);
                $couple->setPosition($currentPosition-1);
                $this->updateFicheMetierTypeActivite($swapWith);
                $this->updateFicheMetierTypeActivite($couple);
            }
        }
    }

    /**
     * @param FicheMetierTypeActivite $couple
     */
    public function moveDown(FicheMetierTypeActivite $couple) {
        $currentPosition = $couple->getPosition();
        $activites = $this->getActivitesByFicheMetierType($couple->getFiche());

        if ($currentPosition < count($activites)) {

            $swapWith = null;
            foreach ($activites as $activite) {
                if ($activite->getPosition() === $currentPosition + 1) {
                    $swapWith = $activite;
                    break;
                }
            }

            if ($swapWith) {
                $swapWith->setPosition($currentPosition);
                $couple->setPosition($currentPosition+1);
                $this->updateFicheMetierTypeActivite($swapWith);
                $this->updateFicheMetierTypeActivite($couple);
            }
        }
    }

    /**
     * @param FicheMetier $fiche
     */
    public function compacting(FicheMetier $fiche) {
        $activites = $this->getActivitesByFicheMetierType($fiche);

        $position = 1;
        foreach ($activites as $activite) {
            $activite->setPosition($position);
            $this->updateFicheMetierTypeActivite($activite);
            $position++;
        }
    }

    /**
     * @param FicheMetier $fiche
     * @param Activite $activite
     * @return FicheMetierTypeActivite
     */
    public function createFicheMetierTypeActivite(FicheMetier $fiche, Activite $activite) : FicheMetierTypeActivite
    {
        $activites = $this->getActivitesByFicheMetierType($fiche);

        $couple = new FicheMetierTypeActivite();
        $couple->setFiche($fiche);
        $couple->setActivite($activite);
        $couple->setPosition(count($activites) + 1);

        try {
            $this->getEntityManager()->persist($couple);
            $this->getEntityManager()->flush($couple);
        } catch (ORMException $e) {
            throw new RuntimeException('Un problème est survenu lors de la création en BD', $e);
        }
        return $couple;
    }

    /**
     * @param FicheMetierTypeActivite $couple
     * @return FicheMetierTypeActivite
     */
    public function updateFicheMetierTypeActivite(FicheMetierTypeActivite $couple) : FicheMetierTypeActivite
    {
        try {
            $this->getEntityManager()->flush($couple);
        } catch (ORMException $e) {
            throw new RuntimeException('Un problème est survenu lors de la mise à jour en BD', $e);
        }
        return $couple;

    }

    /**
     * @param FicheMetierTypeActivite $couple
     */
    public function removeFicheMetierTypeActivite(FicheMetierTypeActivite $couple)
    {
        try {
            $this->getEntityManager()->remove($couple);
            $this->getEntityManager()->flush($couple);
        } catch (ORMException $e) {
            throw new RuntimeException('Un problème est survenu lors de la suppression en BD', $e);
        }

        $this->compacting($couple->getFiche());
    }

    /**
     * @param Activite $activite
     * @param array
     * @return Activite
     */
    public function updateLibelle(Activite $activite,  $data) : Activite
    {
        $user = $this->getUserService()->getConnectedUser();
        $date = $this->getDateTime();

        $current = $activite->getCurrentActiviteLibelle();

        $libelle = null;
        $ok = false;
        if (isset($data['libelle'])) $libelle = $data['libelle'];
        if ($libelle !== null AND trim($libelle) !== '') {
            $activiteLibelle = new ActiviteLibelle();
            $activiteLibelle->setActivite($activite);
            $activiteLibelle->setLibelle($libelle);
            $activiteLibelle->setHistoCreateur($user);
            $activiteLibelle->setHistoCreation($date);
            $activiteLibelle->setHistoModificateur($user);
            $activiteLibelle->setHistoModification($date);
            try {
                $this->getEntityManager()->persist($activiteLibelle);
                $this->getEntityManager()->flush($activiteLibelle);
            } catch (ORMException $e) {
                throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base",0 ,$e);
            }
            $ok = true;
        }

        if ($current !== null AND $ok === true) {
            $current->setHistoDestruction($date);
            $current->setHistoDestructeur($user);
            try {
                $this->getEntityManager()->flush($current);
            } catch (ORMException $e) {
                throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base",0 ,$e);
            }
        }
        return $activite;
    }

    public function getActivitesbyCompetence(Competence $competence)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('libelle.histoDestruction IS NULL')
            ->andWhere('competenceelement.competence = :competence')
            ->setParameter('competence', $competence)
            ->orderBy('libelle.libelle', 'ASC')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

}