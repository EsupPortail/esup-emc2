<?php

namespace Application\Service\Fonction;

use Application\Entity\Db\Fonction;
use Application\Entity\Db\FonctionLibelle;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Octopus\Entity\Db\FonctionType;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FonctionService {
    use EntityManagerAwareTrait;
    use \Octopus\Service\Fonction\FonctionServiceAwareTrait;
    use UserServiceAwareTrait;

    /** @var \Octopus\Service\Fonction\FonctionService */
    public $octopusFonctionService;

    /**
     * @var Fonction $fonction
     * @return Fonction
     */
    public function create($fonction)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $fonction->setHistoCreation($date);
        $fonction->setHistoCreateur($user);
        $fonction->setHistoModification($date);
        $fonction->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($fonction);
            $this->getEntityManager()->flush($fonction);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une Fonction", $e);
        }
        return $fonction;
    }

    /**
     * @var Fonction $fonction
     * @return Fonction
     */
    public function update($fonction)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $fonction->setHistoModification($date);
        $fonction->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($fonction);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une Fonction", $e);
        }
        return $fonction;
    }

    /**
     * @var Fonction $fonction
     * @return Fonction
     */
    public function historise($fonction)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $fonction->setHistoDestruction($date);
        $fonction->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($fonction);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une Fonction", $e);
        }
        return $fonction;
    }

    /**
     * @var Fonction $fonction
     * @return Fonction
     */
    public function restore($fonction)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $fonction->setHistoModification($date);
        $fonction->setHistoModificateur($user);
        $fonction->setHistoDestruction(null);
        $fonction->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($fonction);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une Fonction", $e);
        }
        return $fonction;
    }

    /**
     * @var Fonction $fonction
     * @return Fonction
     */
    public function delete($fonction)
    {
        try {
            $this->getEntityManager()->remove($fonction);
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'effacement d'une Fonction", $e);
        }
        return $fonction;
    }

    /**  ********************/

    /**
     * @var FonctionLibelle $libelle
     * @return FonctionLibelle
     */
    public function createLibelle($libelle)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $libelle->setHistoCreation($date);
        $libelle->setHistoCreateur($user);
        $libelle->setHistoModification($date);
        $libelle->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($libelle);
            $this->getEntityManager()->flush($libelle);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une FonctionLibelle", $e);
        }
        return $libelle;
    }

    /**
     * @var FonctionLibelle $libelle
     * @return FonctionLibelle
     */
    public function updateLibelle($libelle)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $libelle->setHistoModification($date);
        $libelle->setHistoModificateur($user);

        try {
            $this->getEntityManager()->flush($libelle);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une FonctionLibelle", $e);
        }
        return $libelle;
    }

    /**
     * @var FonctionLibelle $libelle
     * @return FonctionLibelle
     */
    public function historiseLibelle($libelle)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $libelle->setHistoDestruction($date);
        $libelle->setHistoDestructeur($user);

        try {
            $this->getEntityManager()->flush($libelle);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une FonctionLibelle", $e);
        }
        return $libelle;
    }

    /**
     * @var FonctionLibelle $libelle
     * @return FonctionLibelle
     */
    public function restoreLibelle($libelle)
    {
        try {
            $date = new DateTime();
        } catch(Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date", $e);
        }
        $user = $this->getUserService()->getConnectedUser();

        $libelle->setHistoModification($date);
        $libelle->setHistoModificateur($user);
        $libelle->setHistoDestruction(null);
        $libelle->setHistoDestructeur(null);

        try {
            $this->getEntityManager()->flush($libelle);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement d'une FonctionLibelle", $e);
        }
        return $libelle;
    }

    /**
     * @var FonctionLibelle $libelle
     * @return FonctionLibelle
     */
    public function deleteLibelle($libelle)
    {
        try {
            $this->getEntityManager()->remove($libelle);
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'effacement d'une FonctionLibelle", $e);
        }
        return $libelle;
    }

    /**  ********************/
    /**
     * @return Fonction[]
     */
    public function getFonctions()
    {
        $qb = $this->getEntityManager()->getRepository(Fonction::class)->createQueryBuilder('fonction')
            ->orderBy('fonction.id');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Fonction
     */
    public function getFonction($id)
    {
        $qb = $this->getEntityManager()->getRepository(Fonction::class)->createQueryBuilder('fonction')
            ->andWhere('fonction.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Fonction partagent le même identifiant [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Fonction
     */
    public function getRequestedFontion($controller, $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        $fonction = $this->getFonction($id);
        return $fonction;
    }


    public function synchroniseFromOctopus()
    {
        $fonctionType = $this->getFonctionService()->getFonctionTypeByNom(FonctionType::FONCTION_STRUCTURELLE);
        $fonctions_OCTOPUS = $this->getFonctionService()->getFonctionsByType($fonctionType);

        foreach ($fonctions_OCTOPUS as $fonction_OCTOPUS) {
            $fonction = $this->createFromOctopus($fonction_OCTOPUS);
        }
    }

    /**
     * @var \Octopus\Entity\Db\Fonction $fonction_OCTOPUS
     * @return Fonction
     */
    private function createFromOctopus($fonction_OCTOPUS)
    {
        $fonction = new Fonction();
        $fonction->setSource('OCTOPUS');
        $fonction->setIdSource($fonction_OCTOPUS->getId());
        $fonction = $this->create($fonction);

        /**  recopie des libelles */
        foreach ($fonction_OCTOPUS->getLibelles() as $libelle_OCTOPUS) {
            $libelle = new FonctionLibelle();
            $libelle->setFonction($fonction);
            $libelle->setLibelle($libelle_OCTOPUS->getLibelle());
            $libelle->setGenre($libelle_OCTOPUS->getGenre());
            $libelle->setDefault(($libelle_OCTOPUS->getDefault())?'O':'N');
            $libelle->setSource('OCTOPUS');
            $libelle->setIdSource($libelle_OCTOPUS->getId());
            $this->createLibelle($libelle);

            $fonction->addLibelle($libelle);
            $this->update($fonction);
        }

        return $fonction;
    }

    public function getFonctionsAsOption()
    {
        $options = [];
        $options[null] = "Selectionner une fonction ...";

        foreach ($this->getFonctions() as $fonction) {
            $options[$fonction->getId()] = $fonction->__toString();
        }

        return $options;
    }
}
