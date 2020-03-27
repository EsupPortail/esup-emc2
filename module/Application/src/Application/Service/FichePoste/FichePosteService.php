<?php

namespace Application\Service\FichePoste;

use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheposteApplicationRetiree;
use Application\Entity\Db\FicheTypeExterne;
use Application\Entity\Db\SpecificitePoste;
use Application\Entity\Db\Structure;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FichePosteService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            ->addSelect('poste')->leftJoin('fiche.poste', 'poste')
            ->addSelect('agent')->leftJoin('fiche.agent', 'agent')
            ->addSelect('specificite')->leftJoin('fiche.specificite', 'specificite')
            ->addSelect('externe')->leftJoin('fiche.fichesMetiers', 'externe')
            ;
        return $qb;
    }

    /**
     * @return FichePoste[]
     */
    public function getFichesPostes()
    {
        $qb = $this->getEntityManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            ->addSelect('poste')->leftJoin('fiche.poste', 'poste')
            ->addSelect('agent')->leftJoin('fiche.agent', 'agent')
            ->addSelect('specificite')->leftJoin('fiche.specificite', 'specificite')
            ->addSelect('externe')->leftJoin('fiche.fichesMetiers', 'externe')
            ->andWhere('fiche.histoDestruction IS NULL')
            ->orderBy('fiche.id', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return FichePoste
     */
    public function getFichePoste($id)
    {
        $qb = $this->getEntityManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            ->andWhere('fiche.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FichePoste paratagent le même identifiant [".$id."]",$e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @param bool $notNull
     * @return FichePoste
     */
    public function getRequestedFichePoste($controller, $paramName = 'fiche-poste', $notNull = false)
    {
        $id = $controller->params()->fromRoute($paramName);
        $fiche = $this->getFichePoste($id);
        if($notNull && !$fiche) throw new RuntimeException("Aucune fiche de trouvée avec l'identifiant [".$id."]");
        return $fiche;

    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function create($fiche)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème s'est produit lors de la récupération des informations d'historisation", $e);
        }
        $fiche->setHistoCreation($date);
        $fiche->setHistoCreateur($user);
        $fiche->setHistoModification($date);
        $fiche->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($fiche);
            $this->getEntityManager()->flush($fiche);
        } catch (ORMException $e) {
            throw new RuntimeException('Un problème est survenu lors de la création en BD', $e);
        }
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function update($fiche)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème s'est produit lors de la récupération des informations d'historisation", $e);
        }
        $fiche->setHistoModification($date);
        $fiche->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($fiche);
        } catch (ORMException $e) {
            throw new RuntimeException('Un problème est survenu lors de la mise à jour en BD', $e);
        }
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function historise($fiche)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème s'est produit lors de la récupération des informations d'historisation", $e);
        }
        $fiche->setHistoDestruction($date);
        $fiche->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($fiche);
        } catch (ORMException $e) {
            throw new RuntimeException('Un problème est survenu lors de l\'historisation en BD', $e);
        }
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function restore($fiche)
    {
        $fiche->setHistoDestruction(null);
        $fiche->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($fiche);
        } catch (ORMException $e) {
            throw new RuntimeException('Un problème est survenu lors de la restauration en BD', $e);
        }
        return $fiche;
    }

    /**
     * @param FichePoste $fiche
     * @return FichePoste
     */
    public function delete($fiche)
    {
        try {
            $this->getEntityManager()->remove($fiche);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException('Un problème est survenu lors de la restauration en BD', $e);
        }
        return $fiche;
    }

    /** SPECIFICITE POSTE  ********************************************************************************************/

    /**
     * @return SpecificitePoste[]
     */
    public function getSpecificitesPostes() {
        $qb = $this->getEntityManager()->getRepository(SpecificitePoste::class)->createQueryBuilder('specificite')
            ->orderBy('specificite.id', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return SpecificitePoste
     */
    public function getSpecificitePoste($id)
    {
        $qb = $this->getEntityManager()->getRepository(SpecificitePoste::class)->createQueryBuilder('specificite')
            ->andWhere('specificite.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs spécificités partagent sur le même identifiant [".$id."].");
        }
        return $result;
    }

    /**
     * @param SpecificitePoste $specificite
     * @return SpecificitePoste
     */
    public function createSpecificitePoste($specificite)
    {
        try {
            $this->getEntityManager()->persist($specificite);
            $this->getEntityManager()->flush($specificite);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la création de la spécificité du poste.", $e);
        }
        return $specificite;
    }

    /**
     * @param SpecificitePoste $specificite
     * @return SpecificitePoste
     */
    public function updateSpecificitePoste($specificite)
    {
        try {
            $this->getEntityManager()->flush($specificite);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la mise à jour de la spécificité du poste.", $e);
        }
        return $specificite;
    }

    /**
     * @param SpecificitePoste $specificite
     */
    public function deleteSpecificitePoste($specificite)
    {
        try {
            $this->getEntityManager()->remove($specificite);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de l'effacement de la spécificité du poste.", $e);
        }
    }

    /** FICHE TYPE EXTERNE ********************************************************************************************/

    /**
     * @param FicheTypeExterne $ficheTypeExterne
     * @return FicheTypeExterne
     */
    public function createFicheTypeExterne($ficheTypeExterne)
    {
        try {
            $this->getEntityManager()->persist($ficheTypeExterne);
            $this->getEntityManager()->flush($ficheTypeExterne);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de l'ajout d'une fiche metier externe.", $e);
        }
        return $ficheTypeExterne;
    }

    /**
     * @param FicheTypeExterne $ficheTypeExterne
     * @return FicheTypeExterne
     */
    public function updateFicheTypeExterne($ficheTypeExterne)
    {
        try {
            $this->getEntityManager()->flush($ficheTypeExterne);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la mise à jour d'une fiche metier externe.", $e);
        }
        return $ficheTypeExterne;
    }

    /**
     * @param FicheTypeExterne $ficheTypeExterne
     * @return FicheTypeExterne
     */
    public function deleteFicheTypeExterne($ficheTypeExterne)
    {
        try {
            $this->getEntityManager()->remove($ficheTypeExterne);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors du retrait d'une fiche metier externe.", $e);
        }
        return $ficheTypeExterne;
    }


    /**
     * @param integer $id
     * @return FicheTypeExterne
     */
    public function getFicheTypeExterne($id)
    {
        $qb = $this->getEntityManager()->getRepository(FicheTypeExterne::class)->createQueryBuilder('externe')
            ->andWhere('externe.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieus FicheTypeExterne partagent le même identifiant [".$id."]",$e);
        }
        return $result;
    }

    /**
     * @return FichePoste
     */
    public function getLastFichePoste()
    {
        $fiches = $this->getFichesPostes();
        return end($fiches);
    }

    /**
     * @param Structure $structure
     * @param boolean $sousstructure
     * @return FichePoste[]
     */
    public function getFichesPostesByStructure($structure, $sousstructure = false)
    {
        try {
            $today = new DateTime();
            $noEnd = DateTime::createFromFormat('d/m/Y H:i:s', '31/12/1999 00:00:00');
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la création des dates");
        }

        $qb = $this->getEntityManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            ->addSelect('agent')->join('fiche.agent', 'agent')
            ->addSelect('poste')->leftJoin('fiche.poste', 'poste')
            ->addSelect('statut')->join('agent.statuts', 'statut')
            ->addSelect('grade')->join('agent.grades', 'grade')
            ->addSelect('structure')->join('grade.structure', 'structure')
            ->andWhere('statut.fin >= :today OR statut.fin IS NULL')
            ->andWhere('grade.dateFin >= :today OR grade.dateFin IS NULL')
            ->andWhere('statut.administratif = :true')
            ->setParameter('today', $today)
            //->setParameter('noEnd', $noEnd)
            ->setParameter('true', 'O')
            ->orderBy('agent.nomUsuel, agent.prenom')
        ;

        if ($structure !== null && $sousstructure === false) {
            $qb = $qb
                ->andWhere('statut.structure = :structure')
                ->setParameter('structure', $structure);
        }
        if ($structure !== null && $sousstructure === true) {
            $qb = $qb
                ->andWhere('statut.structure = :structure OR structure.parent = :structure')
                ->setParameter('structure', $structure);
        }
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Structure $structure
     * @param boolean $sousstructure
     * @return FichePoste[]
     */
    public function getFichesPostesSansAgentByStructure($structure, $sousstructure = false)
    {
        $qb = $this->getEntityManager()->getRepository(FichePoste::class)->createQueryBuilder('fiche')
            ->addSelect('poste')->join('fiche.poste', 'poste')
            ->addSelect('agent')->leftJoin('fiche.agent', 'agent')
            ->addSelect('structure')->join('poste.structure', 'structure')
            ->andWhere('agent.id IS NULL')
            ->orderBy('poste.numeroPoste')
        ;

        if ($structure !== null && $sousstructure === false) {
            $qb = $qb
                ->andWhere('structure = :structure')
                ->setParameter('structure', $structure);
        }
        if ($structure !== null && $sousstructure === true) {
            $qb = $qb
                ->andWhere('structure = :structure OR structure.parent = :structure')
                ->setParameter('structure', $structure);
        }
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * Calcul du set d'applications associées à une fiche de poste et/ou une fiche metier "externe".
     * Va tenir compte de applications conservées (ou retirées par l'auteur de la fiche de poste)
     * @param FichePoste $ficheposte
     * @param FicheMetier $fichemetier
     * @return array
     */
    public function getApplicationsAssocieesFicheMetier(FichePoste $ficheposte, FicheMetier $fichemetier) {

        //provenant de la fiche metier
        $applications = [];
        foreach ($ficheposte->getFichesMetiers() as $fichemetiertype) {
            if ($fichemetiertype->getFicheType() === $fichemetier) {

                //provenant de la fiche metier
                foreach ($fichemetier->getApplications() as $application) {
                    if (!isset($applications[$application->getId()])) {
                        $applications[$application->getId()] = [
                            'entity' => $application,
                            'display' => true,
                            'raisons' => [[ 'Fiche métier' , $fichemetier]]
                        ];
                    } else {
                        $applications[$application->getId()]['raisons'][] = [ 'FicheMetier' , $fichemetier];
                    }
                }

                //provenant des activités
                $keptActivites = explode(";", $fichemetiertype->getActivites());
                foreach ($fichemetier->getActivites() as $activite) {
                    if (array_search($activite->getId(), $keptActivites) !== false) {
                        foreach ($activite->getActivite()->getApplications() as $application) {
                            if (!isset($applications[$application->getId()])) {
                                $applications[$application->getId()] = [
                                    'entity' => $application,
                                    'display' => true,
                                    'raisons' => [[ 'Activité' , $activite->getActivite()]]
                                ];
                            } else {
                                $applications[$application->getId()]['raisons'][] = [ 'Activité' , $activite->getActivite()];
                            }
                        }
                    }
                }

            }
        }

        $retirees = $ficheposte->getApplicationsRetirees();
        /** @var FicheposteApplicationRetiree $conservee */
        foreach ($retirees as $retiree) {
            if ($retiree->getHistoDestruction() === null) $applications[$retiree->getApplication()->getId()]['display'] = false;
        }

        return $applications;
    }

    /**
     * @return FichePoste[]
     */
    public function getFichesPostesSansAgent() {
        $qb = $this->createQueryBuilder()
            ->andWhere('agent.id is NULL')
            ->andWhere('poste.id is NOT NULL')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return FichePoste[]
     */
    public function getFichesPostesSansPoste()
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('poste.id is NULL')
            ->andWhere('agent.id is NOT NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return FichePoste[]
     */
    public function getFichesPostesSansAgentEtPoste()
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('poste.id is NULL')
            ->andWhere('agent.id is NULL')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFichesPostesAvecAgentEtPoste()
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('poste.id is NOT NULL')
            ->andWhere('agent.id is NOT NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}