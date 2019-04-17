<?php

namespace Fichier\Service\Fichier;

use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Fichier\Entity\Db\Fichier;
use Fichier\Entity\Db\Nature;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Entity\Db\User;
use Utilisateur\Service\User\UserServiceAwareTrait;

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
     * Crée un fichier à partir des données d'upload fournies.
     * @param array           $uploadResult Données résultant de l'upload de fichier
     * @param Nature          $nature       Version de fichier
     * @return Fichier fichier
     */
    public function createFichierFromUpload( array $uploadResult, Nature $nature)
    {
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", $e);
        }

        $fichier = null;
        $file = current($uploadResult['files']);

        if (isset($file['name'])) {

            $path = $file['tmp_name'];
            $nomFichier = $file['name'];
            $typeFichier = $file['type'];
            $tailleFichier = $file['size'];

            if (! is_uploaded_file($path)) {
                throw new RuntimeException("Possible file upload attack: " . $path);
            }

//            try {
//                $fichierId = Uuid::uuid4()->toString();
//            } catch (Exception $e) {
//                throw new RuntimeException("Un problème est survenu lors de la création d'identifiant d'un Fichier.", $e);
//            }

            $fichier = new Fichier();
            $fichier
                ->setNature($nature)
                ->setTypeMime($typeFichier)
                ->setNomOriginal($nomFichier)
                ->setTaille($tailleFichier)
            ;
            // à faire en dernier car le formatter exploite des propriétés du Fichier
            $uid = uniqid();
            $fichier->setNomStockage($date->format('Ymd-His')."-".$uid."-".$nature->getCode()."-".$nomFichier);

            //TODO $this->moveUploadedFileForFichier($fichier, $path);
            $this->create($fichier);
        }

        return $fichier;
    }



}