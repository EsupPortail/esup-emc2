<?php

namespace Formation\Service\Lieu;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\Lieu;
use Formation\Entity\Db\Seance;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class LieuService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(Lieu $lieu): Lieu {
        $this->getObjectManager()->persist($lieu);
        $this->getObjectManager()->flush();
        return $lieu;
    }

    public function update(Lieu $lieu): Lieu {
        $this->getObjectManager()->flush();
        return $lieu;
    }

    public function historise(Lieu $lieu): Lieu {
        $lieu->historiser();
        $this->getObjectManager()->flush();
        return $lieu;
    }

    public function restore(Lieu $lieu): Lieu {
        $lieu->dehistoriser();
        $this->getObjectManager()->flush();
        return $lieu;
    }

    public function delete(Lieu $lieu): Lieu {
        $this->getObjectManager()->remove($lieu);
        $this->getObjectManager()->flush();
        return $lieu;
    }

    /** REQUETAGES ****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Lieu::class)->createQueryBuilder('lieu')
            ->leftJoin('lieu.seances', 'seance')->addSelect('seance')
//            ->leftJoin('seance.instance', 'session')->addSelect('session')
//            ->leftJoin('session.formation', 'formation')->addSelect('formation')
        ;
        return $qb;
    }

    public function getLieu(?int $id): ?Lieu
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('lieu.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".self::class."] partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedLieu(AbstractActionController $controller, string $param = "lieu"): ?Lieu
    {
        $id = $controller->params()->fromRoute('lieu');
        $result = $this->getLieu($id);
        return $result;
    }

    /** @return Lieu[] */
    public function getLieux(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('lieu.ville, lieu.campus, lieu.batiment, lieu.libelle', 'ASC');
        if (!$withHisto) {
            $qb = $qb->andWhere('lieu.histoDestruction IS NULL');
        }
        $results = $qb->getQuery()->getResult();
        return $results;
    }

    /** @return Lieu[] */
    public function getLieuxBySeance(Seance $seance, bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('lieu.seance = :seance')->setParameter('seance', $seance)
            ->orderBy('lieu.ville, lieu.campus, lieu.batiment, lieu.libelle', 'ASC');
        if (!$withHisto) {
            $qb = $qb->andWhere('lieu.histoDestruction IS NULL');
        }
        $results = $qb->getQuery()->getResult();
        return $results;
    }

    /** @return Lieu[] */
    public function getLieuxBySession(FormationInstance $session, bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('seance.session = :session')->setParameter('session', $session)
            ->orderBy('lieu.ville, lieu.campus, lieu.batiment, lieu.libelle', 'ASC');
        if (!$withHisto) {
            $qb = $qb->andWhere('lieu.histoDestruction IS NULL')
                ->andWhere('seance.histoDestruction IS NULL')
            ;
        }
        $results = $qb->getQuery()->getResult();
        return $results;
    }

    /** @return Lieu[] */
    public function getLieuxByFormation(Formation $formation, bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('session.formation = :formation')->setParameter('formation', $formation)
            ->orderBy('lieu.ville, lieu.campus, lieu.batiment, lieu.libelle', 'ASC');
        if (!$withHisto) {
            $qb = $qb->andWhere('lieu.histoDestruction IS NULL')
                ->andWhere('seance.histoDestruction IS NULL')
                ->andWhere('session.histoDestruction IS NULL')
            ;
        }
        $results = $qb->getQuery()->getResult();
        return $results;
    }

    /** @return Lieu[]
     * TODO faire mieux
     **/
    public function getLieuxbyTerm(string $term): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere("LOWER(CONCAT(lieu.batiment, ' ', lieu.libelle, ' ', lieu.campus, ' ', lieu.ville)) like :search 
                     OR LOWER(CONCAT(lieu.batiment, ' ', lieu.libelle, ' ', lieu.ville , ' ', lieu.campus)) like :search 
            ")
            ->setParameter('search', '%' . strtolower($term) . '%');
        $result = $qb->getQuery()->getResult();

        $lieux = [];
        /** @var Lieu $item */
        foreach ($result as $item) {
            $lieux[$item->getId()] = $item;
        }
        return $lieux;
    }

    public function getLieuWithInfos(?string $libelle, ?string $batiment, ?string $campus, ?string $ville): ?Lieu
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('lieu.histoDestruction IS NULL')
            ->andWhere('lieu.libelle = :libelle')->setParameter('libelle', $libelle)
            ->andWhere('lieu.batiment = :batiment')->setParameter('batiment', $batiment)
            ->andWhere('lieu.campus = :campus')->setParameter('campus', $campus)
            ->andWhere('lieu.ville = :ville')->setParameter('ville', $ville)
        ;
        try {
            $results = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Lieu::class."] partagent les même info [Libelle:".$libelle."|Batiment:".$batiment."|Campus:".$campus."|Ville:".$ville."]",0,$e);
        }
        return $results;
    }
    /** FACADES *******************************************************************************************************/

    public function createWithData(array $data): Lieu
    {
        $lieu = new Lieu();
        $lieu->setLibelle($data["libelle"]??null);
        $lieu->setBatiment($data["batiment"]??null);
        $lieu->setCampus($data["campus"]??null);
        $lieu->setVille($data["ville"]??null);

        $this->create($lieu);
        return $lieu;
    }

    public function formatLieuJSON(array $lieux): array
    {
        $result = [];
        /** @var Lieu[] $lieux */
        foreach ($lieux as $lieu) {
            $extra = ($lieu->getCampus() || $lieu->getVille()) ? ($lieu->getVille(). " " . $lieu->getCampus()) : "Campus et ville non renseignés";
            $result[] = array(
                'id' => $lieu->getId(),
                'label' => $lieu->getBatiment()." ".$lieu->getLibelle(),
                'extra' => "<span class='badge' style='background-color: slategray;'>" . $extra . "</span>",
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }


}