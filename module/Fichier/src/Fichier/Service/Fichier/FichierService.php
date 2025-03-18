<?php

namespace Fichier\Service\Fichier;

use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Exception;
use Fichier\Entity\Db\Fichier;
use Fichier\Entity\Db\Nature;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class FichierService
{
    use ProvidesObjectManager;
    use UserServiceAwareTrait;

    private ?string $path = null;

    public function setPath(?string $path): FichierService
    {
        $this->path = $path;
        return $this;
    }

    public function create(Fichier $fichier): Fichier
    {
        $this->getObjectManager()->persist($fichier);
        $this->getObjectManager()->flush($fichier);
        return $fichier;
    }

    public function update(Fichier $fichier): Fichier
    {
        $this->getObjectManager()->flush($fichier);
        return $fichier;
    }

    public function historise(Fichier $fichier): Fichier
    {
        $this->getObjectManager()->flush($fichier);
        return $fichier;
    }

    public function restore(Fichier $fichier): Fichier
    {
        $this->getObjectManager()->flush($fichier);
        return $fichier;
    }

    public function delete(Fichier $fichier): Fichier
    {
        $this->getObjectManager()->remove($fichier);
        $this->getObjectManager()->flush();
        return $fichier;
    }

    public function getFichier(?int $id): ?Fichier
    {
        $qb = $this->getObjectManager()->getRepository(Fichier::class)->createQueryBuilder('fichier')
            ->andWhere('fichier.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Fichier partagent le même identifiant [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedFichier(AbstractActionController $controller, string $paramName = 'fichier'): ?Fichier
    {
        $id = $controller->params()->fromRoute($paramName);
        $fichier = $this->getFichier($id);
        return $fichier;
    }

    /**
     * Crée un fichier à partir des données d'upload fournies.
     * @param array $file Données résultant de l'upload de fichier
     * @param Nature $nature Version de fichier
     * @return Fichier fichier
     */
    public function createFichierFromUpload(array $file, Nature $nature): Fichier
    {
        /** @var DateTime $date */
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème lors de la récupération de la date", 0, $e);
        }

        $fichier = null;

        if (isset($file['name'])) {

            $path = $file['tmp_name'];
            $nomFichier = $file['name'];
            $typeFichier = $file['type'];
            $tailleFichier = $file['size'];

            if (!is_uploaded_file($path)) {
                throw new RuntimeException("Possible file upload attack: " . $path);
            }

            $uid = uniqid();

            $fichier = new Fichier();
            $fichier
                ->setId($uid)
                ->setNature($nature)
                ->setTypeMime($typeFichier)
                ->setNomOriginal($nomFichier)
                ->setTaille($tailleFichier);
            $fichier->setNomStockage($date->format('Ymd-His') . "-" . $uid . "-" . $nature->getCode() . "-" . $nomFichier);

            $newPath = $this->path . $fichier->getNomStockage();
            $res = move_uploaded_file($path, $newPath);

            if ($res === false) {
                throw new RuntimeException("Impossible de déplacer le fichier temporaire uploadé de $path vers $newPath");
            }

            $this->create($fichier);
        }
        return $fichier;
    }

    public function fetchContenuFichier(Fichier $fichier): string
    {
        $filePath = $this->path . $fichier->getNomStockage();

        if (!is_readable($filePath)) {
            throw new RuntimeException(
                "Le fichier suivant n'existe pas ou n'est pas accessible sur le serveur : " . $filePath);
        }

        $contenuFichier = file_get_contents($filePath);

        return $contenuFichier;
    }

    public function removeFichier(Fichier $fichier): void
    {
        $path = $this->path . $fichier->getNomStockage();
        $res = unlink($path);
        if ($res === false) {
            throw new RuntimeException("Un problème est survenue lors de l'effacement du fichier");
        }
        $this->delete($fichier);
    }


    public function readCSV(string $fichier_path, bool $explodeMultiline = false): array
    {

        $handle = fopen($fichier_path, "r");
        $array = [];
        while ($content = fgetcsv($handle, 0, ";")) {
            $all = implode("|", $content);
            $encoding = mb_detect_encoding($all, 'UTF-8, ISO-8859-1');
            $content = array_map(function (string $st) use ($encoding) {
                $st = str_replace(chr(63), '\'', $st);
                $st = mb_convert_encoding($st, 'UTF-8', $encoding);
                return $st;
            }, $content);
            $array[] = $content;
        }

        $header = $array[0];
        $data = array_splice($array, 1);

        $jsonData = [];
        foreach ($data as $item) {
            $jsonItem = [];
            foreach ($header as $key => $value) {
                if ($explodeMultiline) {
                    if (strstr($item[$key], PHP_EOL)) {
                        $jsonItem[$value] = explode(PHP_EOL, $item[$key]);
                    } else {
                        $jsonItem[$value] = $item[$key];
                    }
                } else {
                    $jsonItem[$value] = $item[$key];
                }
            }
            $jsonData[] = $jsonItem;
        }

        return $jsonData;
    }
}