<?php

namespace Application\Service\FicheMetier;

use Application\Entity\Db\Domaine;
use Application\Entity\Db\FamilleProfessionnelle;
use Application\Entity\Db\FicheMetier;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractController;

class FicheMetierService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param string $order an attribute use to sort
     * @return FicheMetier[]
     */
    public function getFichesMetiers($order = 'id')
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetier::class)->createQueryBuilder('ficheMetier')
            ->addSelect('metier')->leftJoin('ficheMetier.metier', 'metier')
            ->addSelect('application')->leftJoin('ficheMetier.applications', 'application')
            ->addSelect('formation')->leftJoin('ficheMetier.formations', 'formation')
            ->addSelect('competence')->leftJoin('ficheMetier.competences', 'competence')
            ->orderBy('ficheMetier.', $order)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $id
     * @return FicheMetier
     */
    public function getFicheMetier($id)
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetier::class)->createQueryBuilder('ficheMetier')
            ->andWhere('ficheMetier.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs fiches métiers portent le même identifiant [".$id."].");
        }
        return $result;
    }

    /**
     * @param AbstractController $controller
     * @param string $name
     * @param bool $notNull
     * @return FicheMetier
     */
    public function getRequestedFicheMetier($controller, $name = 'fiche', $notNull = false)
    {
        $ficheId = $controller->params()->fromRoute($name);
        $fiche = $this->getFicheMetier($ficheId);
        if($notNull && !$fiche) throw new RuntimeException("Aucune fiche de trouvée avec l'identifiant [".$ficheId."]");

        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function create($fiche)
    {
        try {
            $connectedUtilisateur = $this->getUserService()->getConnectedUser();
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème de récupération des infos concernant l'utilisateur ou la date", $e);
        }

        $fiche->setHistoCreation($date);
        $fiche->setHistoCreateur($connectedUtilisateur);
        $fiche->setHistoModification($date);
        $fiche->setHistoModificateur($connectedUtilisateur);

        try {
            $this->getEntityManager()->persist($fiche);
            $this->getEntityManager()->flush($fiche);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la création de la fiche.");
        }

        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function update($fiche)
    {
        try {
            $connectedUtilisateur = $this->getUserService()->getConnectedUser();
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème de récupération des infos concernant l'utilisateur ou la date", $e);
        }

        $fiche->setHistoModification($date);
        $fiche->setHistoModificateur($connectedUtilisateur);

        try {
            $this->getEntityManager()->flush($fiche);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la mise à jour de la fiche.");
        }

        return $fiche;
    }

    /**
     * @param FicheMetier $ficheMetier
     * @return FicheMetier
     */
    public function historise($ficheMetier)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème s'est produit lors de la récupération des informations d'historisation", $e);
        }
        $ficheMetier->setHistoDestruction($date);
        $ficheMetier->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($ficheMetier);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de l'historisation de la fiche métier.", $e);
        }
        return $ficheMetier;
    }

    /**
     * @param FicheMetier $ficheMetier
     * @return FicheMetier
     */
    public function restore($ficheMetier)
    {
        $ficheMetier->setHistoDestruction(null);
        $ficheMetier->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($ficheMetier);
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de la restauration de la fiche métier.", $e);
        }
        return $ficheMetier;
    }

    /**
     * @param FicheMetier $ficheMetier
     * @return FicheMetier
     */
    public function delete($ficheMetier)
    {
        try {
            $this->getEntityManager()->remove($ficheMetier);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw new RuntimeException("Une erreur s'est produite lors de l'effacement de la fiche métier.", $e);
        }
        return $ficheMetier;
    }

    /**
     * @return FicheMetier
     */
    public function getLastFicheMetier()
    {
        $fiches = $this->getFichesMetiers('id');
        return end($fiches);
    }

    /**
     * @param FamilleProfessionnelle $famille
     * @return FicheMetier[]
     */
    public function getFicheByFamille($famille)
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetier::class)->createQueryBuilder('fiche')
            ->addSelect('metier')->join('fiche.metier', 'metier')
            ->addSelect('domaine')->join('metier.domaine', 'domaine')
            ->addSelect('famille')->join('domaine.famille', 'famille')
            ->andWhere('famille = :famille')
            ->setParameter('famille', $famille)
            ->orderBy('metier.libelle')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Domaine $domaine
     * @return FicheMetier[]
     */
    public function getFicheByDomaine($domaine)
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetier::class)->createQueryBuilder('fiche')
            ->addSelect('metier')->join('fiche.metier', 'metier')
            ->addSelect('domaine')->join('metier.domaine', 'domaine')
            ->addSelect('famille')->join('domaine.famille', 'famille')
            ->andWhere('domaine = :domaine')
            ->setParameter('domaine', $domaine)
            ->orderBy('metier.libelle')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

}