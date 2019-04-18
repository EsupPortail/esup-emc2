<?php

namespace Fichier\Service\Fichier;

use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Fichier\Entity\Db\Fichier;
use Fichier\Entity\Db\Nature;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Entity\Db\User;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FichierService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param Fichier $fichier
     * @return Fichier
     */
    public function create($fichier)
    {
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }
        $fichier->setHistoCreateur($user);
        $fichier->setHistoCreation($date);
        $fichier->setHistoModificateur($user);
        $fichier->setHistoModification($date);

        $this->getEntityManager()->persist($fichier);
        try {
            $this->getEntityManager()->flush($fichier);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la création d'un Fichier.", $e);
        }
        return $fichier;
    }

    /**
     * @param Fichier $fichier
     * @return Fichier
     */
    public function update($fichier)
    {
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }
        $fichier->setHistoModificateur($user);
        $fichier->setHistoModification($date);

        try {
            $this->getEntityManager()->flush($fichier);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la mise à jour d'un Fichier.", $e);
        }
        return $fichier;
    }

    /**
     * @param Fichier $fichier
     * @return Fichier
     */
    public function historise($fichier)
    {
        /** @var User $user */
        $user = $this->getUserService()->getConnectedUser();
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }
        $fichier->setHistoDestructeur($user);
        $fichier->setHistoDestruction($date);

        try {
            $this->getEntityManager()->flush($fichier);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de l'historisation d'un Fichier.", $e);
        }
        return $fichier;
    }

    /**
     * @param Fichier $fichier
     * @return Fichier
     */
    public function restaure($fichier)
    {
        $fichier->setHistoDestructeur(null);
        $fichier->setHistoDestruction(null);

        try {
            $this->getEntityManager()->flush($fichier);
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la restauration d'un Fichier.", $e);
        }
        return $fichier;
    }

    /**
     * @param Fichier $fichier
     * @return Fichier
     */
    public function delete($fichier)
    {
        $this->getEntityManager()->remove($fichier);
        try {
            $this->getEntityManager()->flush();
        } catch (OptimisticLockException $e) {
            throw new RuntimeException("Un problème s'est produit lors de la suppression d'un Fichier.", $e);
        }
        return $fichier;
    }

    /**
     * @param integer $id
     * @return Fichier
     */
    public function getFichier($id)
    {
        $qb = $this->getEntityManager()->getRepository(Fichier::class)->createQueryBuilder('fichier')
            ->andWhere('fichier.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Fichier partagent le même identifiant [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Fichier
     */
    public function getRequestedFichier($controller, $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        $fichier = $this->getFichier($id);
        return $fichier;
    }

    /**
     * Crée un fichier à partir des données d'upload fournies.
     * @param array           $file Données résultant de l'upload de fichier
     * @param Nature          $nature       Version de fichier
     * @return Fichier fichier
     */
    public function createFichierFromUpload($file, $nature)
    {
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }

        $fichier = null;

        if (isset($file['name'])) {

            $path = $file['tmp_name'];
            $nomFichier = $file['name'];
            $typeFichier = $file['type'];
            $tailleFichier = $file['size'];

            if (! is_uploaded_file($path)) {
                throw new RuntimeException("Possible file upload attack: " . $path);
            }

            $uid = uniqid();

            $fichier = new Fichier();
            $fichier
                ->setId($uid)
                ->setNature($nature)
                ->setTypeMime($typeFichier)
                ->setNomOriginal($nomFichier)
                ->setTaille($tailleFichier)
            ;
            $fichier->setNomStockage($date->format('Ymd-His')."-".$uid."-".$nature->getCode()."-".$nomFichier);

            $newPath = '/app/upload/' . $fichier->getNomStockage();
            $res = move_uploaded_file($path, $newPath);

            if ($res === false) {
                throw new RuntimeException("Impossible de déplacer le fichier temporaire uploadé de $path vers $newPath");
            }

            $this->create($fichier);
        }
        return $fichier;
    }

    /**
     * Retourne le contenu d'un Fichier sous la forme d'une chaîne de caractères.
     *
     * @param Fichier $fichier
     * @return string
     */
    public function fetchContenuFichier(Fichier $fichier)
    {
        $filePath = '/app/upload/' . $fichier->getNomStockage();

        if (! is_readable($filePath)) {
            throw new RuntimeException(
                "Le fichier suivant n'existe pas ou n'est pas accessible sur le serveur : " . $filePath);
        }

        $contenuFichier = file_get_contents($filePath);

        return $contenuFichier;
    }

    public function removeFichier(Fichier $fichier)
    {
        $path = '/app/upload' . $fichier->getNomStockage();
        $res = unlink($path);
        if ($res === false) {
            throw new RuntimeException("Un problème est survenue lors de l'effacement du fichier");
        }
        $this->delete($fichier);
    }


}